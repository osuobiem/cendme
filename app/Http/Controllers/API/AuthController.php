<?php

namespace App\Http\Controllers\API;

use App\Credential;
use App\Http\Controllers\Controller;
use App\Order;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // String of English letters
    private $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Transaction types
    private $transaction_types = [
        'user_fund_wallet', 'agent_fund_wallet', 'agent_withdrawal', 'vendor_withdrawal', 'pay_for_order'
    ];

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

    /**
     * Initialize Payment
     * @return json
     */
    public function initialize(Request $request)
    {
        // Get validation rules
        $validate = $this->init_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ]);
        }

        $originator = $request->user();
        $type = $request['type'];
        $amount = $request['amount'];

        $transaction = new Transaction();

        // Extract actor from transaction type
        $actor = explode('_', $type)[0];

        switch ($actor) {
            default:
                $transaction->user_id = $originator->id;

                // Check if user wants to pay for order directly
                if ($type == 'pay_for_order') {
                    $order = Order::where('user_id', $originator->id)
                        ->where('status', 'pending')->first();

                    if (!$order) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid transaction. No pending order for this user.'
                        ]);
                    }

                    $order_amount = json_decode($order->amount)->total;

                    if ($amount != $order_amount) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid amount'
                        ]);
                    }

                    $transaction->order_id = $order->id;
                }
                break;
        }

        // Fill new transaction object
        $ref = $this->generate_ref();
        $transaction->reference = $ref;
        $transaction->amount = $amount;
        $transaction->type = $type;

        // Try to save transaction or catch error if any
        try {
            $transaction->save();

            return response()->json([
                'success' => true,
                'message' => 'Transaction Initialized',
                'data' => [
                    "reference" => $ref
                ]
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * Payment Init Validation Rules
     * @return object The validator object
     */
    private function init_rules(Request $request)
    {
        $types = implode(',', $this->transaction_types);

        // Make and return validation rules
        return Validator::make($request->all(), [
            'type' => 'required|in:' . $types,
            'amount' => 'required|numeric'
        ]);
    }

    /**
     * Generate unique payment reference
     * @return string
     */
    public function generate_ref()
    {
        $seg1 = substr(str_shuffle($this->alpha), 0, 10);
        $seg2 = date('YmdHis');

        return 'TRANS-' . $seg1 . '-' . $seg2;
    }
}
