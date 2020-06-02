<?php

namespace App\Http\Controllers\API;

use App\Agent;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            $user = auth()->guard('agents-web')->user();

            // Create access token
            $token = $user->createToken('Agent Access Token');

            // Compose response data
            $data = [
                'agent' => $user,
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
        $agent->surname = ucfirst(strtolower($request['surname']));
        $agent->firstname = ucfirst(strtolower($request['firstname']));
        $agent->email = strtolower($request['email']);
        $agent->phone = $request['phone'];
        $agent->password = Hash::make(strtolower($request['password']));

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
            'surname' => 'required|alpha',
            'firstname' => 'required|alpha',
            'email' => 'required|email|unique:agents',
            'phone' => 'required|numeric|unique:agents|digits:11',
            'password' => 'required|alpha_dash|min:6|max:30'
        ]);
    }
    // -----------

    // UPDATE AGENT
    /**
     * Update agent data
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
        $user = Agent::find($id);

        if ($user) {
            // Assign user object properties
            $user->firstname = ucfirst(strtolower($request['firstname']));
            $user->lastname = ucfirst(strtolower($request['lastname']));
            $user->phone = $request['phone'];
            $user->gender = ucfirst(strtolower($request['gender']));
            $user->address = $request['address'];
            if ($request['password']) {
                $user->password = Hash::make(strtolower($request['password']));
            }

            // Try user save or catch error if any
            try {
                $user->save();
                return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
            } catch (\Throwable $th) {
                Log::error($th);
                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }
        } else {
            return ['success' => false, 'status' => 404, 'message' => 'No user exists with this ID'];
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
            'gender' => 'required|alpha|min:4|max:6',
            'address' => 'required|min:4',
            'password' => 'alpha_dash|min:6|max:30'
        ]);
    }
    // ------------
}
