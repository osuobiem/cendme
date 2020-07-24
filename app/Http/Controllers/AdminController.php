<?php

namespace App\Http\Controllers;

use App\Agent;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // ADMIN LOGIN

    /**
     * Login admin
     * @return json
     */
    public function login(Request $request)
    {
        // Get validation rules
        $validate = $this->login_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        $credentials = $credentials = $request->only('username', 'password');

        // Attempt admin login
        $attempt = Auth::guard('admins')->attempt($credentials, $request['remember_me']);

        $res = [
            'success' => false,
            'message' => 'Invalid credentials'
        ];

        if ($attempt) {
            return ['success' => true, 'message' => "Login Successful"];
        } else {
            return response()->json($res, 400);
        }
    }

    /**
     * Vendor Login Validation Rules
     * @return object The validator object
     */
    public function login_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|alpha_dash'
        ]);
    }

    /**
     * Logout admin
     * @return object
     */
    public function logout()
    {
        Auth::logout();

        return redirect('admin/login');
    }

    // ---------------

    // VENDOR

    /**
     * Delete vendor
     * @param int $id Vendor ID
     * @return json
     */
    public function delete_vendor($id)
    {
        // Find vendor with supplied id
        $vendor = Vendor::find($id);

        if ($vendor) {

            // Try vendor delete or catch error if any
            try {
                $vendor->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Vendor deleted'
                ]);
            } catch (\Throwable $th) {
                Log::error($th);

                // Return failure response
                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error'
                ]);
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vendor not found'
                ],
                404
            );
        }
    }

    //  -----------

    // AGENT
    /**
     * Delete agent
     * @param int $id Agent ID
     * @return json
     */
    public function delete_agent($id)
    {
        // Find agent with supplied id
        $agent = Agent::find($id);

        if ($agent) {

            // Try agent delete or catch error if any
            try {
                $agent->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Shopper deleted'
                ]);
            } catch (\Throwable $th) {
                Log::error($th);

                // Return failure response
                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error'
                ]);
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Shopper not found'
                ],
                404
            );
        }
    }
    // ------------

    // USER
    /**
     * Delete user
     * @param int $id User ID
     * @return json
     */
    public function delete_user($id)
    {
        // Find user with supplied id
        $user = User::find($id);

        if ($user) {

            // Try user delete or catch error if any
            try {
                $user->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User deleted'
                ]);
            } catch (\Throwable $th) {
                Log::error($th);

                // Return failure response
                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error'
                ]);
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User not found'
                ],
                404
            );
        }
    }
    // ------------
}
