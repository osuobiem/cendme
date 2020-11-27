<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword as ResetPassword;
use App\PasswordReset;
use App\Shopper;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Home/Landing Page
     * @return view
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Password Reset Page
     * @return view
     */
    public function reset_password($token = false)
    {
        if ($token) {
            $password_reset = PasswordReset::where('reset_token', $token)->firstOrFail();

            return strtotime($password_reset->will_expire) < time()
                ? view('reset-password-after', ['error' => true, 'reset_token' => null])
                : view('reset-password-after', ['error' => false, 'reset_token' => $token]);
        } else {
            return view('reset-password');
        }
    }

    /**
     * Process password reset
     * @return json
     */
    public function process_password_reset(Request $request)
    {
        // Get validation rules
        $validate = $this->reset_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        $email = $request['email'];
        $token = md5(rand(1, 100) . '-' . time());
        $vendor = Vendor::where('email', $email)->first();

        $password_reset = new PasswordReset();
        $password_reset->reset_token = $token;
        $password_reset->email = $email;
        $password_reset->user_type = 'vendor';
        $password_reset->will_expire = date('Y-m-d H:i:s', time() + 86400);

        // Try Save and Send mail
        try {
            Mail::to($vendor)->send(new ResetPassword($token));

            $password_reset->save();

            return response()->json([
                'success' => true,
                'message' => 'Password Reset Email Sent!'
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error!'
            ], 500);
        }
    }

    /**
     * Password Validation Rules
     * @return object The validator object
     */
    private function reset_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'email' => 'required|email|exists:vendors,email'
        ]);
    }

    /**
     * Set New Password
     * @return json
     */
    public function set_new_password(Request $request) {
        $token = $request['reset_token'];

        $password_reset = PasswordReset::where('reset_token', $token)->firstOrFail();
        switch ($password_reset->user_type) {
            case 'vendor':
                $originator = Vendor::where('email', $password_reset->email)->firstOrfail();
                break;

            case 'shopper':
                $originator = Shopper::where('email', $password_reset->email)->firstOrfail();
                break;

            default:
                $originator = User::where('email', $password_reset->email)->firstOrfail();
                break;
        }

        // Set new password
        $originator->password = Hash::make(strtolower($request['password']));

        // Clear token
        $password_reset->reset_token = null;

        // Try Save
        try {
            $originator->save();
            $password_reset->save();

            return response()->json([
                'success' => true,
                'message' => 'Password Reset Successful'
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
