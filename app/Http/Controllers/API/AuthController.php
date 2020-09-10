<?php

namespace App\Http\Controllers\API;

use App\Credential;
use App\Http\Controllers\Controller;
use App\Order;
use App\Shopper;
use App\Transaction;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // String of English letters
    private $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Transaction types
    private $transaction_types = [
        'fund_wallet', 'complete_order'
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
     * Finalize Payment/Transaction
     * @return json
     */
    public function finalize(Request $request)
    {
        // Get validation rules
        $validate = $this->fin_rules($request);

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

        $data = [];

        // Initial response message
        $message = 'Transaction Finalized';

        switch ($type) {
                // Fund Wallet
            case 'fund_wallet':
                if ($originator->level_id) {
                    $transaction->shopper_id = $originator->id;
                } else {
                    $transaction->user_id = $originator->id;
                }

                if ($request['status']) {
                    $originator->balance += $request['amount'];

                    // Try to save order or catch error if any
                    try {
                        $originator->save();
                        $originator->photo = $originator->level_id ? url('/') . Storage::url('shoppers/' . $originator->photo) : url('/') . Storage::url('users/' . $originator->photo);
                        $data = $originator;
                    } catch (\Throwable $th) {
                        Log::error($th);
                        return response()->json([
                            'success' => false,
                            'message' => 'Internal Server Error'
                        ], 500);
                    }
                }
                break;

                // Pay for Order
            default:
                // Check if order reference is in request object
                if (!$request['order_ref']) {
                    return response()->json([
                        'success' => false,
                        'message' => [
                            'order_ref' => [
                                'The order ref field is required'
                            ]
                        ]
                    ]);
                }

                $transaction->user_id = $originator->id;

                $order = Order::where('reference', $request['order_ref'])->first();

                // Check if the order exists
                if (!$order) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order not found'
                    ]);
                }

                $price = json_decode($order->amount)->products;
                $shoppers = $this->get_qualified_shoppers($price, $originator->area_id);

                // Check if any shopper qualified
                if (count($shoppers) < 1) {
                    $message = "No suitable shopper found!";
                }

                // Check if the order has not been completed
                if ($order->status != 'pending') {
                    $originator->photo = url('/') . Storage::url('users/' . $originator->photo);

                    // Send request notification data
                    $this->fire_add_data($order->reference, $shoppers);
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'data' => $originator
                    ]);
                }

                $order_amount = json_decode($order->amount)->total;

                if ($amount != $order_amount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid amount'
                    ]);
                }

                if (!$request['direct_pay']) {
                    // Check if user's wallet balance is sufficient
                    if ($originator->balance < $amount) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Insufficient Balance'
                        ]);
                    }

                    $originator->balance -= $amount;
                }

                $transaction->order_id = $order->id;

                if ($request['status']) {
                    $order->status = 'paid';

                    // Try to save order or catch error if any
                    try {
                        $order->save();
                        $originator->save();
                        $originator->photo = url('/') . Storage::url('users/' . $originator->photo);
                        $data = $originator;

                        // Send request notification data
                        $this->fire_add_data($order->reference, $shoppers);
                    } catch (\Throwable $th) {
                        Log::error($th);
                        return response()->json([
                            'success' => false,
                            'message' => 'Internal Server Error'
                        ], 500);
                    }
                }

                break;
        }

        // Fill new transaction object
        $transaction->reference = $request['ref'];
        $transaction->amount = $amount;
        $transaction->type = $type;
        $transaction->status = $request['status'];

        // Try to save transaction or catch error if any
        try {
            $transaction->save();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
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
     * Payment/Transaction Finalization Validation Rules
     * @return object The validator object
     */
    private function fin_rules(Request $request)
    {
        $types = implode(',', $this->transaction_types);

        // Make and return validation rules
        return Validator::make($request->all(), [
            'type' => 'required|in:' . $types,
            'amount' => 'required|numeric',
            'status' => 'required|boolean',
            'ref' => 'required',
            'direct_pay' => 'boolean'
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

    /**
     * Store order request notification on cloud firestore
     * @param string $order_ref Order Reference
     * @param array $agents Qualified Shoppers array
     */
    public function fire_add_data($order_ref, $shoppers)
    {
        // Initialize Firestore Client
        $firestore = new FirestoreClient();

        // Get Collection, document and store data
        $order_not = $firestore->collection('order-requests')->document($order_ref);
        $order_not->set([
            'shoppers' => $shoppers,
            'created_at' => date('Y-m-d h:i'),
            'expires_at' => date('Y-m-d h:i', time() + 900),
            'status' => 0
        ]);
    }

    public function get_qualified_shoppers($price, $area)
    {
        // Get qualified shoppers
        $shoppers = Shopper::where('area_id', $area)->where('balance', '>=', $price)->where('verified', true)->get();

        $ids = [];
        foreach ($shoppers as $shopper) {
            array_push($ids, $shopper->id);
        }

        return $ids;
    }
}
