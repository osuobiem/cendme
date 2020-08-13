<?php

namespace App\Http\Controllers\API;

use App\Credential;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get Paystack Credentials
     * @return json
     */
    public function get_paystack()
    {
        $credentials = Credential::where('key', 'paystack_secret_key')
            ->orWhere('key', 'paystack_public_key')->get();

        $formatted_cred = [];

        foreach ($credentials as $cred) {
            $formatted_cred[$cred->key] = $cred->value;
        }

        return response()->json([
            'success' => true,
            'message' => 'Fetch Successful',
            'data' => [
                'credentials' => $formatted_cred
            ]
        ]);
    }
}
