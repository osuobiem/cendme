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
        $attempt = Auth::guard('agents_web')->attempt($credentials);

        if ($attempt) {
            $agent = auth()->guard('agents_web')->user();

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
}
