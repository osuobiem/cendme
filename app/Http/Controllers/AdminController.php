<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
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
    // ------------------
}
