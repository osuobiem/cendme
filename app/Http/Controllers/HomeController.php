<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword as ResetPassword;
use App\PasswordReset;
use App\Vendor;
use Illuminate\Http\Request;
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
    public function reset_password($token = false) {
        return !$token ? view('reset-password') : view('reset-password-after');
    }

    /**
     * Process password reset
     * @return json
     */
    public function process_password_reset(Request $request) {
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
        $password_reset->will_expire = date('Y-m-d H:i:s', time()+86400);

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
}
