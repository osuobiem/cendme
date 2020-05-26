<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
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
                "status" => 400,
                "errors" => $validate->errors()
            ], 400);
        }

        // Store vendor data
        $store = $this->cstore($request);
        return response()->json($store, $store['status']);
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
        $vendor->business_name = ucfirst(strtolower($request['business_name']));
        $vendor->email = strtolower($request['email']);
        $vendor->phone = $request['phone'];
        $vendor->address = $request['address'];
        $vendor->password = Hash::make(strtolower($request['password']));

        // Try vendor save or catch error if any
        try {
            $vendor->save();
            $data = $vendor::where('email', $vendor->email)->first();
            return ['status' => 200, 'data' => $data];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['status' => 500, 'errors' => 'Internal Server Error'];
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
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric',
            'address' => 'required|min:4',
            'password' => 'required|alpha_dash|min:6|max:30'
        ]);
    }
}
