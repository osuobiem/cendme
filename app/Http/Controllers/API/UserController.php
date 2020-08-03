<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
            'password' => 'required|alpha_dash|min:6|max:30'
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

        // Check if photo is available
        if ($request['photo']) {
            $upload = $this->update_photo($request, $user);

            // Check upload status
            if (!$upload['success']) {
                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }

            $user = $upload['user'];
        }

        // Try user save or catch error if any
        try {
            $user->save();

            // Delete old photo
            if ($request['photo']) {
                $old_photo = $upload['old_photo'];
                $old_photo != 'placeholder.png' ? Storage::delete('/public/users/' . $old_photo) : '';
            }

            // Get photo url
            $user->photo = url('/') . Storage::url('users/' . $user->photo);

            return ['success' => true, 'status' => 200, 'message' => 'Update Successful', 'data' => ['user' => $user]];
        } catch (\Throwable $th) {
            Log::error($th);

            // Delete uploaded photo
            if ($request['photo']) {
                $user->photo != 'placeholder.png' ? Storage::delete('/public/users/' . $user->photo) : '';
            }

            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Update user photo
     * @param oject $user The user object
     * @return array
     */
    public function update_photo(Request $request, $user)
    {
        $stored = false;
        $old_photo = $user->photo;

        // Try photo upload
        $photo = $request['photo'];
        $stored = Storage::put('/public/users', $photo);

        if ($stored) {
            $user->photo = basename($stored);
            return [
                'success' => true,
                'user' => $user,
                'old_photo' => $old_photo
            ];
        } else {
            return [
                'success' => false
            ];
        }
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
}
