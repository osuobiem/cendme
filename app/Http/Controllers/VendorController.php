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

    /**
     * Login vendor
     * @return json $response
     */
    public function login(Request $request)
    {
        $credentials = $credentials = $request->only('email', 'password');

        // Attempt vendor login
        $attempt = Auth::guard('vendors')->attempt($credentials);

        $res = [
            'success' => false,
            'message' => [
                'email' => 'Invalid credentials.'
            ]
        ];

        if ($attempt) {
            return ['success' => true, 'message' => "Login Successful"];
        } else {
            return response()->json($res, 400);
        }
    }

    /**
     * Create new vendor
     * @return json $response
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
            ], 400);
        }

        // Store vendor data
        $store = $this->cstore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Update vendor data
     * @param int $id Vendor id to update with
     * @return json $response
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
            return ['success' => false, 'status' => 404, 'message' => 'No vendor exists with this ID'];
        }
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

        // Try vendor save or catch error if any
        try {
            $vendor->save();
            $data = $vendor::where('email', $vendor->email)->first();
            return ['success' => true, 'status' => 200, 'message' => 'Signup Successful', 'data' => $data];
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
            'business_name' => 'required|min:1',
            'email' => 'required|email|unique:vendors',
            'phone' => 'required|numeric',
            'address' => 'required|min:4',
            'password' => 'required|alpha_dash|min:6|max:30'
        ]);
    }

    /**
     * Vendor Update Validation Rules
     * @return object The validator object
     */
    private function update_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'business_name' => 'required|min:1',
            'phone' => 'required|numeric',
            'address' => 'required|min:4',
            'password' => 'alpha_dash|min:6|max:30'
        ]);
    }
}
