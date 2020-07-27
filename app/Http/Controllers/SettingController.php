<?php

namespace App\Http\Controllers;

use App\Credential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    // LOAD SETTINGS
    /**
     * Load Settings after password auth
     * @param string $page Page to load
     * @return html
     */
    public function settings(Request $request, $page)
    {
        // Get validation rules
        $validate = $this->pass_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Verify password
        $check_pass = Hash::check($request['password'], Auth::guard('admins')->user()->password);

        if (!$check_pass) {
            return response()->json(['success' => false, 'status' => 400, 'message' => 'Invalid Password'], 400);
        } else {

            // Get data to attach to view
            switch ($page) {
                default:
                    $data = Credential::get();
                    break;
            }

            return view('admin.settings.' . $page, ['data' => $data]);
        }
    }

    /**
     * Password Auth Validation Rules
     * @return object The validator object
     */
    private function pass_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'password' => 'required'
        ]);
    }
    // ------------------

    // CREDENTIAL SETTINGS
    /**
     * Update credentials
     * @param string $type Credential Type
     * @return object
     */
    public function credentials(Request $request, $type)
    {
        // Get validation rules
        $validate = $this->cred_update_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Select credential type
        switch ($type) {
            case 'paystack':
                $data = $request->only(['secret_key', 'public_key']);

                // Perform update
                $store = $this->update_paystack($data);
                $status = $store['status'];
                unset($store['status']);
                return response()->json($store, $status);
                break;

            default: // Google
                $data = $request->only(['api_key']);

                // Perform update
                $store = $this->update_google($data);
                $status = $store['status'];
                unset($store['status']);
                return response()->json($store, $status);
                break;
        }
    }

    /**
     * Update Paystack Credentials
     * @param array $data Data to update
     * @return array
     */
    public function update_paystack($data)
    {
        // Try paystack credential upadte or catch error if any
        try {
            // Update credentials
            Credential::where('key', 'paystack_secret_key')->update(['value' => $data['secret_key']]);
            Credential::where('key', 'paystack_public_key')->update(['value' => $data['public_key']]);

            return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Update Google Credentials
     * @param array $data Data to update
     * @return array
     */
    public function update_google($data)
    {
        // Try google credential upadte or catch error if any
        try {
            // Update credentials
            Credential::where('key', 'google_api_key')->update(['value' => $data['api_key']]);

            return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Credentials Update Validation Rules
     * @return object The validator object
     */
    private function cred_update_rules(Request $request)
    {
        // Custom validation message
        $messages = [
            'required_without' => 'The :attribute field is required.',
        ];

        // Make and return validation rules
        return Validator::make($request->all(), [
            'secret_key' => 'required_without:api_key',
            'public_key' => 'required_without:api_key',
            'api_key' => 'required_without:secret_key,public_key'
        ], $messages);
    }
    // ------------------
}
