<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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
                "data" => $validate->errors()
            ]);
        }

        // Store user data
        return response()->json($this->store($request));
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
        $user->password = Hash::make(strtolower($request['gender']));

        // Try user save or catch error if any
        try {
            $user->save();
            return ['status' => 200, 'data' => 'Successful'];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['status' => 500, 'data' => 'Internal Server Error'];
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
            'password' => 'required|alpha_dash|min:8|max:20'
        ]);
    }
}
