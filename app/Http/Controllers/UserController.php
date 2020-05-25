<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Create new user
     * @return json $response
     */
    public function create(Request $request)
    {
        // Get validation rules
        $validate = $this->create_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validate->errors()
            ], 400);
        }

        // Store user data
        $store = $this->cstore($request);
        return response()->json($store, $store['status']);
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
        $user->firstname = ucfirst(strtolower($request['firstname']));
        $user->lastname = ucfirst(strtolower($request['lastname']));
        $user->email = strtolower($request['email']);
        $user->phone = $request['phone'];
        $user->password = Hash::make(strtolower($request['password']));

        // Try user save or catch error if any
        try {
            $user->save();
            $data = $user::where('email', $user->email)->first();
            return ['status' => 200, 'data' => $data];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['status' => 500, 'errors' => 'Internal Server Error'];
        }
    }

    /**
     * Login user
     * @return json $response
     */
    public function login(Request $request)
    {
        $credentials = $credentials = $request->only('email', 'password');

        // Attempt user login
        $attempt = Auth::attempt($credentials);

        $res = [
            'status' => 400,
            'errors' => [
                'email' => 'Invalid credentials.'
            ]
        ];

        if ($attempt) {
            $data = User::where('email', $credentials['email'])->first();
            return ['status' => 200, 'data' => $data];
        } else {
            return response()->json($res, 400);
        }
    }

    /**
     * Update user data
     * @param int $id User id to update with
     * @return json $response
     */
    public function update(Request $request, $id)
    {
        // Get validation rules
        $validate = $this->update_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validate->errors()
            ], 400);
        }

        // Store user data
        $store = $this->ustore($request, $id);
        return response()->json($store, $store['status']);
    }

    /**
     * Process user data update
     * @param int $id User id to update with
     * @return array Update status
     */
    public function ustore(Request $request, $id)
    {
        // Decode user id
        $id = base64_decode($id);

        // Find user with supplied id
        $user = User::find($id);

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
                return ['status' => 200, 'errors' => 'Update Successful'];
            } catch (\Throwable $th) {
                Log::error($th);
                return ['status' => 500, 'errors' => 'Internal Server Error'];
            }
        } else {
            return ['status' => 404, 'errors' => 'No user exists with this ID'];
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
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|alpha_dash|min:6|max:30'
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
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'phone' => 'required|numeric',
            'gender' => 'required|alpha|min:4|max:6',
            'address' => 'required|min:4',
            'password' => 'alpha_dash|min:6|max:30'
        ]);
    }
}
