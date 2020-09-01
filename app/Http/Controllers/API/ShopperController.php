<?php

namespace App\Http\Controllers\API;

use App\Shopper;
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

class ShopperController extends Controller
{
    // SHOPPER LOGIN
    /**
     * Login shopper without validation checks
     * @return array Response array
     */
    private function fast_login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt shopper login
        $attempt = Auth::guard('shoppers-web')->attempt($credentials);

        if ($attempt) {
            $shopper = auth()->guard('shoppers-web')->user();

            // Create access token
            $token = $shopper->createToken('Shopper Access Token');

            // Get shopper photo url
            $shopper->photo = url('/') . Storage::url('shoppers/' . $shopper->photo);

            // Compose response data
            $data = [
                'shopper' => $shopper,
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
     * Login shopper
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

        // Attempt shopper login
        $attempt = Auth::guard('shoppers-web')->attempt($credentials);
        if ($attempt) {
            // Get shopper object
            $shopper = auth()->guard('shoppers-web')->user();

            // Create access token
            $token = $shopper->createToken('Shopper Access Token');

            // Get shopper photo url
            $shopper->photo = url('/') . Storage::url('shoppers/' . $shopper->photo);

            // Compose response data
            $data = [
                'shopper' => $shopper,
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
            return response()->json($res);
        }
    }
    // -----------


    // SHOPPER SIGNUP
    /**
     * Create new shopper
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
            ]);
        }

        // Store shopper data
        $store = $this->cstore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process shopper creation
     * @return array Result of saved shopper data
     */
    public function cstore(Request $request)
    {
        // New shopper object
        $shopper = new Shopper();

        // Assign shopper object properties
        $shopper->email = strtolower($request['email']);
        $shopper->password = Hash::make(strtolower($request['password']));
        $shopper->level_id = 1;

        // Try shopper save or catch error if any
        try {
            $shopper->save();

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
     * Shopper Creation Validation Rules
     * @return object The validator object
     */
    private function create_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'email' => 'required|email|unique:shoppers',
            'password' => 'required|alpha_dash|min:6|max:30'
        ]);
    }
    // -----------


    // UPDATE SHOPPER

    // After verification
    /**
     * Update shopper data (after verification)
     * @param int $id Shopper id to update with
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
            ]);
        }

        // Store shopper data
        $store = $this->ustore($request, $id);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process shopper data update
     * @param int $id Shopper id to update with
     * @return array Update status
     */
    public function ustore(Request $request, $id)
    {
        // Decode shopper id
        $id = base64_decode($id);

        // Find shopper with supplied id
        $shopper = Shopper::find($id);

        if ($shopper) {
            // Assign shopper object properties
            if ($request['about']) {
                $shopper->about = $request['about'];
            }
            $shopper->address = $request['address'];
            $shopper->lga_id = $request['lga'];

            if ($request['password']) {
                $shopper->password = Hash::make(strtolower($request['password']));
            }

            // Try shopper save or catch error if any
            try {
                $shopper->save();
                return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
            } catch (\Throwable $th) {
                Log::error($th);
                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }
        } else {
            return ['success' => false, 'status' => 200, 'message' => 'Shopper not found'];
        }
    }

    /**
     * Shopper Update Validation Rules
     * @return object The validator object
     */
    private function update_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
            'address' => 'required|min:4',
            'area' => 'required|numeric|exists:areas,id',
            'password' => 'alpha_dash|min:6|max:30'
        ]);
    }

    // Before Verification
    /**
     * Update shopper data (before verification)
     * @param int $id Shopper id to update with
     * @return json
     */
    public function update_b(Request $request, $id)
    {
        // Decode shopper id
        $id = base64_decode($id);

        // Find shopper with supplied id
        $shopper = Shopper::find($id);

        if ($shopper) {

            // Get validation rules
            $validate = $this->update_rules_b($request, $shopper);

            // Run validation
            if ($validate->fails()) {
                return response()->json([
                    "success" => false,
                    "message" => $validate->errors()
                ], 400);
            }

            // Store shopper data
            $store = $this->ustore_b($request, $shopper);
            $status = $store['status'];
            unset($store['status']);
            return response()->json($store, $status);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Shopper not found'
            ], 404);
        }
    }

    /**
     * Process shopper data update (before verifiction)
     * @param object $shopper Shopper object
     * @return array Update status
     */
    public function ustore_b(Request $request, $shopper)
    {
        // Assign shopper object properties
        $shopper->firstname = ucfirst(strtolower($request['firstname']));
        $shopper->lastname = ucfirst(strtolower($request['lastname']));
        $shopper->gender = $request['gender'];
        $shopper->phone = $request['phone'];
        $shopper->dob = date('Y-m-d', strtotime($request['dob']));
        $shopper->bvn = $request['bvn'];
        if ($request['about']) {
            $shopper->about = $request['about'];
        }
        $shopper->address = $request['address'];
        $shopper->lga_id = $request['lga'];

        if ($request['password']) {
            $shopper->password = Hash::make(strtolower($request['password']));
        }

        // Try shopper save or catch error if any
        try {
            $shopper->save();
            return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Shopper Update Validation Rules (before verification)
     * @param object $shopper Shopper Object
     * @return object The validator object
     */
    private function update_rules_b(Request $request, $shopper)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'phone' => [
                'required', 'numeric', 'digits:11',
                // Ignore current shopper from phone uniqueness validation
                Rule::unique('shoppers')->ignore($shopper->id),
            ],
            'gender' => 'required|alpha|min:4|max:6',
            'bvn' => [
                'required', 'numeric', 'digits:11',
                // Ignore current shopper from bvn uniqueness validation
                Rule::unique('shoppers')->ignore($shopper->id),
            ],
            'dob' => 'required|date',
            'address' => 'required|min:4',
            'lga' => 'required|numeric|exists:lgas,id',
            'password' => 'alpha_dash|min:6|max:30',
        ]);
    }
    // ------------


    // SHOPPER VERIFICATION
    /**
     * Verify shopper's identity using bvn + paystack API endpoint
     * @param $id ID of the shopper to be verified
     * @return json
     */
    public function verify($id)
    {
        // decode base64 id
        $id = base64_decode($id);

        // Find shopper with supplied id
        $shopper = Shopper::find($id);

        if ($shopper) {

            // Try to retrieve already saved bvn data
            $bvn_data = $shopper->bvn_data;

            if ($bvn_data) {
                // Try shopper verification
                $errors = $this->check_shopper($bvn_data, $shopper);

                // Do extra BVN check
                if ($bvn_data->bvn != $shopper->bvn) {
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

                // Update shopper verification status
                $shopper->verified = true;
                $shopper->save();

                // Return success response
                return response()->json(["success" => true, "message" => "Shopper verified"], 200);
            } else {
                // Retrieve necessary credentials
                $credentials = Credential::where('key', 'paystack_secret_key')->first();

                // Ping Paystack's BVN API
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $credentials->value
                ])->get('https://api.paystack.co/bank/resolve_bvn/' . $shopper->bvn);

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
                    $bvn_data->shopper_id = $shopper->id;

                    $bvn_data->save();

                    // Try shopper verification

                    $data = (object) $data;
                    $errors = $this->check_shopper($data, $shopper);

                    // Return error if verification fails
                    if ($errors) {
                        return response()->json([
                            "success" => false,
                            "message" => $errors
                        ], 400);
                    }

                    // Update shopper verification status
                    $shopper->verified = true;
                    $shopper->save();

                    // Return success response
                    return response()->json(["success" => true, "message" => "Shopper verified"], 200);
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
            return ['success' => false, 'status' => 404, 'message' => 'Shopper not found'];
        }
    }

    /**
     * Match BVN data against strored shopper data
     * @param object $data BVN Data
     * @param object $shopper Stored shopper object
     * 
     * @return array Array of errors if any
     */
    private function check_shopper($data, $shopper)
    {
        $errors = [];

        if (strtolower($data->first_name) != strtolower($shopper->firstname)) {
            array_push($errors, [
                'firstname' => [
                    'Firstname does not match BVN records'
                ]
            ]);
        }

        if (strtolower($data->last_name) != strtolower($shopper->lastname)) {
            array_push($errors, [
                'lastname' => [
                    'Lastname does not match BVN records'
                ]
            ]);
        }

        if ($data->formatted_dob != date('Y-m-d', strtotime($shopper->dob))) {
            array_push($errors, [
                'dob' => [
                    'Date of birth does not match BVN records'
                ]
            ]);
        }

        if ($data->mobile != $shopper->phone) {
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