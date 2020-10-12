<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Credential;
use App\Vendor;
use App\Vendor_account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        return redirect('vendor/login');
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
        $vendor->area_id = $request['area'];
        $vendor->qr_token = md5(rand(1, 100) . '-' . time());

        // Try vendor save or catch error if any
        try {
            $vendor->save();

            // Attempt login
            $login = $this->fast_login($request);

            return ['success' => true, 'status' => 200, 'message' => 'Sign up Successful', 'data' => ['login' => $login]];
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
            'area' => 'required|numeric|exists:areas,id',
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
            $vendor->area_id = $request['area'];
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
            'area' => 'required|numeric|exists:areas,id'
        ]);
    }

    /**
     * Update photo
     * @param string $id Base64 encoded vendor id
     * @return json
     */
    public function update_photo(Request $request, $id)
    {
        // Get validation rules
        $validate = $this->update_photo_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Decode vendor id
        $id = base64_decode($id);

        // Find vendor with supplied id
        $vendor = Vendor::find($id);

        if ($vendor) {
            $stored = false;
            $old_photo = $vendor->photo;

            // Try photo upload
            $photo = $request['photo'];
            $stored = Storage::put('/public/vendors', $photo);

            if ($stored) {
                $vendor->photo = basename($stored);

                // Try to update vendor profile
                try {
                    $vendor->save();
                    $old_photo != 'placeholder.png' ? Storage::delete('/public/vendors/' . $old_photo) : '';

                    return response()->json([
                        "success" => true,
                        "message" => "Photo Updated Successfully"
                    ]);
                } catch (\Throwable $th) {
                    Log::error($th);
                    $vendor->photo != 'placeholder.png' ? Storage::delete('/public/vendors/' . $vendor->photo) : '';

                    return response()->json([
                        "success" => false,
                        "message" => "Internal Server Error"
                    ], 500);
                }
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Could not upload photo"
                ], 400);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "Vendor not found"
            ], 404);
        }
    }

    /**
     * Update photo validation rules
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
     * Update bank details
     * @return json
     */
    public function update_bank_details(Request $request)
    {
        // Get validation rules
        $validate = $this->update_bank_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Extract data from request
        $account_number = $request['account_number'];
        $account_name = $request['account_name'];
        $bank_id = $request['bank'];

        // Get bank
        $bank = Bank::find($bank_id);

        // VERIFY ACCOUNT NUMBER
        // Retrieve necessary credentials
        $credentials = Credential::where('key', 'paystack_secret_key')->first();

        // Ping Paystack's Resolve Account Number API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $credentials->value
        ])->get('https://api.paystack.co/bank/resolve?account_number=' . $account_number . '&bank_code=' . $bank->code);
        // ---------------

        $vendor_account = Auth::user()->account;
        if ($vendor_account) {
            $account = $vendor_account;
        } else {
            $account = new Vendor_account();
        }

        if ($response->successful()) {
            $account->account_number = $account_number;
            $account->account_name = $account_name;
            $account->bank_id = $bank_id;
            $account->vendor_id = Auth::user()->id;
            $account->verified = true;

            // Save verified vendor bank details
            $account->save();

            return response()->json([
                "success" => true,
                "message" => "Update Successful"
            ]);
        } else {
            $account->account_number = $account_number;
            $account->account_name = $account_name;
            $account->bank_id = $bank_id;
            $account->vendor_id = Auth::user()->id;
            $account->verified = false;

            // Save unverified vendor bank details
            $account->save();

            return response()->json([
                "success" => false,
                "message" => "Could not verify bank account. Check provided credentials"
            ], 400);
        }
    }

    /**
     * Update bank details validation rules
     * @return object The validator object
     */
    private function update_bank_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'account_name' => 'required',
            'account_number' => 'required|numeric|digits:10',
            'bank' => 'numeric|exists:banks,id'
        ]);
    }
    // -------------

    // TRANSACTION
    public function withdraw(Request $request)
    {
        return response()->json([
            "success" => false,
            "message" => 'Still in process'
        ], 400);
    }
    // -----------
}
