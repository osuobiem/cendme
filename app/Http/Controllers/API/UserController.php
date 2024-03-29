<?php

namespace App\Http\Controllers\API;

use App\Area;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Order;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // USER LOGIN
    /**
     * Login user without validation checks
     * @return array Response array
     */
    private function fast_login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt user login
        $attempt = Auth::guard('users-web')->attempt($credentials);

        if ($attempt) {
            $user = auth()->guard('users-web')->user();

            // Create access token
            $token = $user->createToken('User Access Token');

            // Get user photo url
            $user->photo = url('/') . Storage::url('users/' . $user->photo);

            // Compose response data
            $data = [
                'user' => $user,
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
     * Login user
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
            ]);
        }

        // Initial failure response
        $res = [
            'success' => false,
            'message' => 'Invalid credentials.'
        ];

        $credentials = $request->only('email', 'password');

        // Attempt user login
        $attempt = Auth::guard('users-web')->attempt($credentials);
        if ($attempt) {
            // Get user object
            $user = auth()->guard('users-web')->user();

            // Create access token
            $token = $user->createToken('User Access Token');

            // Check if user has logged in from a new device
            if ($user->device_unique != $request['device_unique']) {
                $user->device_unique = $request['device_unique'];

                // Try user save or catch error if any
                try {
                    $user->save();
                } catch (\Throwable $th) {
                    Log::error($th);
                    return response()->json([
                        'success' => 500,
                        'message' => 'Internal Server Error'
                    ]);
                }
            }

            // Get user photo url
            $user->photo = url('/') . Storage::url('users/' . $user->photo);

            // Compose response data
            $data = [
                'user' => $user,
                'area' => $user->area ? $user->area->name : null,
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
            return response()->json($res, 200);
        }
    }
    // -----------

    /**
     * User Login Validation Rules
     * @return object The validator object
     */
    private function login_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_unique' => 'required'
        ]);
    }

    // USER SIGNUP
    /**
     * Create new user
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
            ], 200);
        }

        // Store user data
        $store = $this->cstore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process user creation
     * @return array Result of saved user data
     */
    public function cstore(Request $request)
    {
        // New user object
        $user = new User();

        // Assign user object properties
        $user->name = ucfirst(strtolower($request['name']));
        $user->email = strtolower($request['email']);
        $user->phone = $request['phone'];
        $user->password = Hash::make(strtolower($request['password']));
        $user->device_unique = $request['device_unique'];
        $user->area_id = $request['area'];

        // Try user save or catch error if any
        try {
            $user->save();

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
                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * User Creation Validation Rules
     * @return object The validator object
     */
    private function create_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|digits:11',
            'password' => 'required|alpha_dash|min:6|max:30',
            'device_unique' => 'required',
            'area' => 'required|numeric|exists:areas,id',
        ]);
    }
    // -----------


    // UPDATE USER
    /**
     * Update user data
     * @return json
     */
    public function update(Request $request)
    {
        // Get validation rules
        $validate = $this->update_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 200);
        }

        // Store user data
        $store = $this->ustore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process user data update
     * @return array Update status
     */
    public function ustore(Request $request)
    {
        $user = $request->user();

        // Assign user object properties
        $user->name = ucfirst(strtolower($request['name']));
        $user->phone = $request['phone'];
        $user->gender = ucfirst(strtolower($request['gender']));
        $user->address = $request['address'];
        $user->area_id = $request['area'];
        if ($request['password']) {
            $user->password = Hash::make(strtolower($request['password']));
        }

        // Try user save or catch error if any
        try {
            $user->save();

            // Get photo url
            $user->photo = url('/') . Storage::url('users/' . $user->photo);

            return ['success' => true, 'status' => 200, 'message' => 'Update Successful', 'data' => ['user' => $user]];
        } catch (\Throwable $th) {
            Log::error($th);

            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Update user photo
     * @return array
     */
    public function update_photo(Request $request)
    {
        // Get validation rules
        $validate = $this->update_photo_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ]);
        }

        $user = $request->user();

        $stored = false;
        $old_photo = $user->photo;

        // Try photo upload
        $photo = $request['photo'];
        $stored = Storage::put('/public/users', $photo);

        if ($stored) {
            $user->photo = basename($stored);

            try {
                $user->save();

                // Delete old photo
                $old_photo != 'placeholder.png' ? Storage::delete('/public/users/' . $old_photo) : '';

                // Get photo url
                $user->photo = url('/') . Storage::url('users/' . $user->photo);

                return response()->json(['success' => true, 'message' => 'Update Successful', 'data' => ['user' => $user]]);
            } catch (\Throwable $th) {
                Log::error($th);

                // Delete uploaded photo
                if ($request['photo']) {
                    $user->photo != 'placeholder.png' ? Storage::delete('/public/users/' . $user->photo) : '';
                }

                return response()->json(['success' => false, 'message' => 'Internal Server Error'], 500);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * User Update Photo Validation Rules
     * @return object The validator object
     */
    private function update_photo_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'photo' => 'required|image|max:5120'
        ]);
    }

    /**
     * User Update Validation Rules
     * @return object The validator object
     */
    private function update_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'gender' => 'required|alpha|min:4|max:6',
            'address' => 'required|min:4',
            'area' => 'required|numeric|exists:areas,id',
            'password' => 'alpha_dash|min:6|max:30',
            'photo' => 'image|max:5120'
        ]);
    }
    // ------------
    
    /**
     * Send Password Reset Email
     * @return json
     */
    public function reset_password(Request $request) {
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
        $user = User::where('email', $email)->first();

        $password_reset = new PasswordReset();
        $password_reset->reset_token = $token;
        $password_reset->email = $email;
        $password_reset->user_type = 'user';
        $password_reset->will_expire = date('Y-m-d H:i:s', time()+86400);

        // Try Save and Send mail
        try {
            Mail::to($user)->send(new ResetPassword($token));

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
            'email' => 'required|email|exists:users,email'
        ]);
    }

    /**
     * Confirm Delivery
     * @param string $order_ref Order reference
     * 
     * @return json
     */
    public function confirm_delivery($order_ref) {
        $order = Order::where('reference', $order_ref)->firstOrFail();

        if($order->status != 'in transit') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not in transit'
            ]);
        }

        $order->status = 'delivered';
        // $shopper = $order->shopper;
        
        // $amount = json_decode($order->amount);
        // $shopper_amount = $amount->products + $amount->shopper_transport_fare + ($amount->products * ($shopper->level->commision/100));

        // $shopper->balance += $shopper_amount;
        // $shopper->free = true;

        // Try Save
        try {
            // $shopper->save();
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Delivery Confirmed'
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
