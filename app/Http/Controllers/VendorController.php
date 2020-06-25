<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    // VENDOR LOGIN
    /**
     * Login vendor without validation checks
     * @return bool
     */
    private function fast_login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt vendor login
        return Auth::attempt($credentials);
    }

    /**
     * Login vendor
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

        $credentials = $credentials = $request->only('email', 'password');

        // Attempt vendor login
        $attempt = Auth::attempt($credentials, $request['remember_me']);

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
            'email' => 'required|email',
            'password' => 'required|alpha_dash'
        ]);
    }

    /**
     * Logout vendor
     * @return object
     */
    public function logout()
    {
        Auth::logout();

        return url('vendor/login');
    }
    // -------------

    // VENDOR SIGNUP
    /**
     * Vendor signup
     * @return json
     */
    public function signup(Request $request)
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

        // Store vendor data
        $store = $this->cstore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process vendor creation
     * @return array Result of saved vendor data
     */
    public function cstore(Request $request)
    {
        // New vendor object
        $vendor = new Vendor();

        // Assign vendor object properties
        $vendor->business_name = $request['business_name'];
        $vendor->email = strtolower($request['email']);
        $vendor->phone = $request['phone'];
        $vendor->address = $request['address'];
        $vendor->password = Hash::make(strtolower($request['password']));
        $vendor->lga_id = $request['lga'];

        // Try vendor save or catch error if any
        try {
            $vendor->save();

            // Attempt login
            $login = $this->fast_login($request);

            return ['success' => true, 'status' => 200, 'message' => 'Signup Successful', 'data' => ['login' => $login]];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Vendor Creation Validation Rules
     * @return object The validator object
     */
    private function create_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'business_name' => 'required',
            'email' => 'required|email|unique:vendors',
            'phone' => 'required|numeric|digits:11',
            'address' => 'required|min:4',
            'lga' => 'required|numeric|exists:lgas,id',
            'password' => 'required|alpha_dash|min:6|max:30'
        ]);
    }
    // -------------


    // UPDATE VENDOR
    /**
     * Update vendor data
     * @param int $id Vendor id to update with
     * @return json
     */
    public function update(Request $request, $id)
    {
        // Get validation rules
        $validate = $this->update_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Store vendor data
        $store = $this->ustore($request, $id);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process vendor data update
     * @param int $id Vendor id to update with
     * @return array Update status
     */
    public function ustore(Request $request, $id)
    {
        // Decode vendor id
        $id = base64_decode($id);

        // Find vendor with supplied id
        $vendor = Vendor::find($id);

        if ($vendor) {
            // Assign vendor object properties
            $vendor->business_name = $request['business_name'];
            $vendor->phone = $request['phone'];
            $vendor->address = $request['address'];
            $vendor->lga_id = $request['lga'];
            if ($request['password']) {
                $vendor->password = Hash::make(strtolower($request['password']));
            }

            // Try vendor save or catch error if any
            try {
                $vendor->save();
                return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
            } catch (\Throwable $th) {
                Log::error($th);
                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }
        } else {
            return ['success' => false, 'status' => 404, 'message' => 'Vendor not found'];
        }
    }

    /**
     * Vendor Update Validation Rules
     * @return object The validator object
     */
    private function update_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'business_name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'address' => 'required|min:4',
            'password' => 'alpha_dash|min:6|max:30',
            'lga' => 'required|numeric|exists:lgas,id'
        ]);
    }
    // -------------
}
