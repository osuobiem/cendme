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
        $store = $this->store($request);
        return response()->json($store, $store['status']);
    }

    /**
     * Store user data
     * @return array Result of saving user data
     */
    public function store(Request $request)
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
}
