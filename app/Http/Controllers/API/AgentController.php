<?php

namespace App\Http\Controllers\API;

use App\Agent;
use App\BVN_Data;
use App\Credential;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    // AGENT LOGIN
    /**
     * Login agent without validation checks
     * @return array Response array
     */
    private function fast_login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt agent login
        $attempt = Auth::guard('agents-web')->attempt($credentials);

        if ($attempt) {
            $agent = auth()->guard('agents-web')->user();

            // Create access token
            $token = $agent->createToken('Agent Access Token');

            // Get agent photo url
            $agent->photo = url('/') . Storage::url('agents/' . $agent->photo);

            // Compose response data
            $data = [
                'agent' => $agent,
                'token' => $token->accessToken,
                'token_type' => 'Bearer',
                'token_expires' => Carbon::parse(
                    $token->token->expires_at
                )->toDateTimeString(),
            ];

            return $data;
        } else {
            return false;
        }
    }

    /**
     * Login agent
     * @return json
     */
    public function login(Request $request)
    {
        // Initial failure response
        $res = [
            'success' => false,
            'message' => 'Invalid credentials.'
        ];

        $credentials = $request->only('email', 'password');

        // Attempt agent login
        $attempt = Auth::guard('agents-web')->attempt($credentials);
        if ($attempt) {
            // Get agent object
            $agent = auth()->guard('agents-web')->user();

            // Create access token
            $token = $agent->createToken('Agent Access Token');

            // Get agent photo url
            $agent->photo = url('/') . Storage::url('agents/' . $agent->photo);

            // Compose response data
            $data = [
                'agent' => $agent,
                'token' => $token->accessToken,
                'token_type' => 'Bearer',
                'token_expires' => Carbon::parse(
                    $token->token->expires_at
                )->toDateTimeString(),
            ];

            // Send success response
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Login Successful',
                    'data' => $data
                ],
                200
            );
        } else {
            return response()->json($res, 400);
        }
    }
    // -----------


    // AGENT SIGNUP
    /**
     * Create new agent
     * @return json
     */
    public function create(Request $request)
    {
        // Get validation rules
        $validate = $this->create_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Store agent data
        $store = $this->cstore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process agent creation
     * @return array Result of saved agent data
     */
    public function cstore(Request $request)
    {
        // New agent object
        $agent = new Agent();

        // Assign agent object properties
        $agent->email = strtolower($request['email']);
        $agent->password = Hash::make(strtolower($request['password']));
        $agent->level_id = 1;

        // Try agent save or catch error if any
        try {
            $agent->save();

            // Attempt auto login
            $login = $this->fast_login($request);
            if ($login) {

                // Send success response
                return [
                    'success' => true,
                    'status' => 200,
                    'message' => 'Signup Successful',
                    'data' => $login
                ];
            } else {
                return ['success' => false, 'status' => 500, 'message' => 'Server Error'];
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Agent Creation Validation Rules
     * @return object The validator object
     */
    private function create_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'email' => 'required|email|unique:agents',
            'password' => 'required|alpha_dash|min:6|max:30'
        ]);
    }
    // -----------


    // UPDATE AGENT

    // After verification
    /**
     * Update agent data (after verification)
     * @param int $id Agent id to update with
     * @return json
     */
    public function update(Request $request, $id)
    {
        // Get validation rules
        $validate = $this->update_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Store agent data
        $store = $this->ustore($request, $id);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process agent data update
     * @param int $id Agent id to update with
     * @return array Update status
     */
    public function ustore(Request $request, $id)
    {
        // Decode agent id
        $id = base64_decode($id);

        // Find agent with supplied id
        $agent = Agent::find($id);

        if ($agent) {
            // Assign agent object properties
            if ($request['about']) {
                $agent->about = $request['about'];
            }
            $agent->address = $request['address'];
            $agent->lga_id = $request['lga'];

            if ($request['password']) {
                $agent->password = Hash::make(strtolower($request['password']));
            }

            // Try agent save or catch error if any
            try {
                $agent->save();
                return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
            } catch (\Throwable $th) {
                Log::error($th);
                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }
        } else {
            return ['success' => false, 'status' => 404, 'message' => 'Agent not found'];
        }
    }

    /**
     * Agent Update Validation Rules
     * @return object The validator object
     */
    private function update_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'address' => 'required|min:4',
            'lga' => 'required|numeric|exists:lgas,id',
            'password' => 'alpha_dash|min:6|max:30'
        ]);
    }

    // Before Verification
    /**
     * Update agent data (before verification)
     * @param int $id Agent id to update with
     * @return json
     */
    public function update_b(Request $request, $id)
    {
        // Decode agent id
        $id = base64_decode($id);

        // Find agent with supplied id
        $agent = Agent::find($id);

        if ($agent) {

            // Get validation rules
            $validate = $this->update_rules_b($request, $agent);

            // Run validation
            if ($validate->fails()) {
                return response()->json([
                    "success" => false,
                    "message" => $validate->errors()
                ], 400);
            }

            // Store agent data
            $store = $this->ustore_b($request, $agent);
            $status = $store['status'];
            unset($store['status']);
            return response()->json($store, $status);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Agent not found'
            ], 404);
        }
    }

    /**
     * Process agent data update (before verifiction)
     * @param object $agent Agent object
     * @return array Update status
     */
    public function ustore_b(Request $request, $agent)
    {
        // Assign agent object properties
        $agent->firstname = ucfirst(strtolower($request['firstname']));
        $agent->lastname = ucfirst(strtolower($request['lastname']));
        $agent->gender = $request['gender'];
        $agent->phone = $request['phone'];
        $agent->dob = date('Y-m-d', strtotime($request['dob']));
        $agent->bvn = $request['bvn'];
        if ($request['about']) {
            $agent->about = $request['about'];
        }
        $agent->address = $request['address'];
        $agent->lga_id = $request['lga'];

        if ($request['password']) {
            $agent->password = Hash::make(strtolower($request['password']));
        }

        // Try agent save or catch error if any
        try {
            $agent->save();
            return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Agent Update Validation Rules (before verification)
     * @param object $agent Agent Object
     * @return object The validator object
     */
    private function update_rules_b(Request $request, $agent)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'phone' => [
                'required', 'numeric', 'digits:11',
                // Ignore current agent from phone uniqueness validation
                Rule::unique('agents')->ignore($agent->id),
            ],
            'gender' => 'required|alpha|min:4|max:6',
            'bvn' => [
                'required', 'numeric', 'digits:11',
                // Ignore current agent from bvn uniqueness validation
                Rule::unique('agents')->ignore($agent->id),
            ],
            'dob' => 'required|date',
            'address' => 'required|min:4',
            'lga' => 'required|numeric|exists:lgas,id',
            'password' => 'alpha_dash|min:6|max:30',
        ]);
    }
    // ------------


    // AGENT VERIFICATION
    /**
     * Verify agent's identity using bvn + paystack API endpoint
     * @param $id ID of the agent to be verified
     * @return json
     */
    public function verify($id)
    {
        // decode base64 id
        $id = base64_decode($id);

        // Find agent with supplied id
        $agent = Agent::find($id);

        if ($agent) {

            // Try to retrieve already saved bvn data
            $bvn_data = $agent->bvn_data;

            if ($bvn_data) {
                // Try agent verification
                $errors = $this->check_agent($bvn_data, $agent);

                // Do extra BVN check
                if ($bvn_data->bvn != $agent->bvn) {
                    array_push($errors, [
                        "bvn" => [
                            "Invalid BVN - Please update or confirm from your bank."
                        ]
                    ]);
                }

                // Return error if verification fails
                if ($errors) {
                    return response()->json([
                        "success" => false,
                        "message" => $errors
                    ], 400);
                }

                // Update agent verification status
                $agent->verified = true;
                $agent->save();

                // Return success response
                return response()->json(["success" => true, "message" => "Agent verified"], 200);
            } else {
                // Retrieve necessary credentials
                $credentials = Credential::where('key', 'paystack_secret_key')->first();

                // Ping Paystack's BVN API
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $credentials->value
                ])->get('https://api.paystack.co/bank/resolve_bvn/' . $agent->bvn);

                if ($response->successful()) {
                    $data = $response->json()['data'];

                    // Save retrieved bvn data
                    $bvn_data = new BVN_Data();

                    $bvn_data->first_name = $data['first_name'];
                    $bvn_data->last_name = $data['last_name'];
                    $bvn_data->dob = $data['dob'];
                    $bvn_data->formatted_dob = $data['formatted_dob'];
                    $bvn_data->mobile = $data['mobile'];
                    $bvn_data->bvn = $data['bvn'];
                    $bvn_data->agent_id = $agent->id;

                    $bvn_data->save();

                    // Try agent verification

                    $data = (object) $data;
                    $errors = $this->check_agent($data, $agent);

                    // Return error if verification fails
                    if ($errors) {
                        return response()->json([
                            "success" => false,
                            "message" => $errors
                        ], 400);
                    }

                    // Update agent verification status
                    $agent->verified = true;
                    $agent->save();

                    // Return success response
                    return response()->json(["success" => true, "message" => "Agent verified"], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => [
                            "bvn" => [
                                "Invalid BVN - Please update or confirm from your bank."
                            ]
                        ]
                    ], 400);
                }
            }
        } else {
            return ['success' => false, 'status' => 404, 'message' => 'Agent not found'];
        }
    }

    /**
     * Match BVN data against strored agent data
     * @param object $data BVN Data
     * @param object $agent Stored agent object
     * 
     * @return array Array of errors if any
     */
    private function check_agent($data, $agent)
    {
        $errors = [];

        if (strtolower($data->first_name) != strtolower($agent->firstname)) {
            array_push($errors, [
                'firstname' => [
                    'Firstname does not match BVN records'
                ]
            ]);
        }

        if (strtolower($data->last_name) != strtolower($agent->lastname)) {
            array_push($errors, [
                'lastname' => [
                    'Lastname does not match BVN records'
                ]
            ]);
        }

        if ($data->formatted_dob != date('Y-m-d', strtotime($agent->dob))) {
            array_push($errors, [
                'dob' => [
                    'Date of birth does not match BVN records'
                ]
            ]);
        }

        if ($data->mobile != $agent->phone) {
            array_push($errors, [
                'phone' => [
                    'Phone number does not match BVN records'
                ]
            ]);
        }

        return $errors;
    }
    // ------------
}
