<?php

namespace App\Http\Controllers\API;

use App\Credential;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\Shopper;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class AuthController extends Controller
{
    // String of English letters
    private $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Transaction types
    private $transaction_types = [
        'fund_wallet', 'complete_order'
    ];

    /**
     * Get Payment Credentials
     * @return json
     */
    public function get_credentials()
    {
        $credentials = Credential::where('key', 'paystack_secret_key')
            ->orWhere('key', 'paystack_public_key')
            ->orWhere('key', 'flutter_public_key')
            ->orWhere('key', 'flutter_secret_key')
            ->orWhere('key', 'flutter_enc_key')->get();

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

                $order = Order::where('reference', $request['order_ref'])->firstOrFail();

                // Check if the order has been paid for
                if ($order->status == 'paid') {
                    $originator->photo = url('/') . Storage::url('users/' . $originator->photo);

                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'data' => ['order' => $order]
                    ]);
                }

                // Check if the order is in another state
                if ($order->status != 'pending') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order has already been paid for'
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

                $shoppers = $this->get_eligible_shoppers($originator->area_id);

                // Check if any shopper eligible
                if (count($shoppers) < 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No eligible shopper found!'
                    ]);
                }

                $transaction->order_id = $order->id;

                if ($request['status']) {
                    $order->status = 'paid';

                    // Try to save order or catch error if any
                    try {
                        // Loop through shoppers to extract device unique/token
                        $device_tokens = [];
                        foreach ($shoppers as $shopper) {
                            array_push($device_tokens, $shopper->device_unique);
                        }

                        // Send order request notification
                        $body = 'Will you shop for ' . explode(' ', $order->user->name)[0] . '?';

                        $dt = ["type" => "order", "ref" => $order->reference];
                        $send = $this->send_request_notification($device_tokens, $body, $dt);

                        if (!$send) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Internal Server Error'
                            ], 500);
                        }

                        $order->save();
                        $originator->save();
                        $originator->photo = url('/') . Storage::url('users/' . $originator->photo);
                        $data = ['order' => $order];
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
     * Send notification to shopper devices
     * @param array $device_tokens Device tokens of eligible shoppers
     * @param string $body Body of the notification
     * @param array $data Data to attach to notification
     * 
     * @return bool
     */
    public function send_request_notification($device_tokens, $body, $data)
    {
        // Initialize Firebase Cloud Messaging Component
        $messaging = app('firebase.messaging');

        // Send Notification to devices
        foreach ($device_tokens as $token) {
            try {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification(Notification::create('Cendme Order Request', $body))
                    ->withData($data);

                $messaging->sendMulticast($message, $device_tokens);
                return true;
            } catch (\Throwable $th) {
                Log::error($th);
                return false;
            }
        }
    }

    /**
     * Get Eligible shoppers to execute order
     * @param int $area Area ID
     * @return array
     */
    public function get_eligible_shoppers($area)
    {
        // Get eligible shoppers
        $shoppers = Shopper::where('area_id', $area)->where('balance', '>=', 5000)->where('verified', true)->get();

        $shs = [];
        foreach ($shoppers as $shopper) {
            array_push($shs, $shopper);
        }

        return $shs;
    }

    /**
     * Compose Notification data
     * @param object $order
     * @return array
     */
    public function compose_notification_data($order)
    {
        $products = json_decode($order->products);
        $data = [];
        $vendors = [];

        foreach ($products as $i => $product) {
            $p = Product::findOrFail($product->id);
            $vendor = $p->vendor;

            // Product data
            $p_data = [
                'id' => $p->id,
                'title' => $p->title,
                'photo' => url('/') . Storage::url('products/' . $p->photo),
                'price' => $p->price,
                'quantity' => $product->quantity
            ];

            // Compose vendor data

            if (isset($vendors[$vendor->id])) {
                array_push($vendors[$vendor->id]["products"], $p_data);
            } else {
                $v = [
                    "id" => $vendor->id,
                    "name" => $vendor->business_name,
                    "phone" => $vendor->phone,
                    "address" => $vendor->address,
                    "photo" => url('/') . Storage::url('vendors/' . $vendor->photo),
                    "products" => []
                ];

                array_push($v["products"], $p_data);

                $vendors[$vendor->id] = $v;
            }
        }

        // Compose response data
        foreach ($vendors as $vendor) {
            array_push($data, $vendor);
        }

        // Get user
        $user = $order->user;
        $user->photo = url('/') . Storage::url('users/' . $user->photo);

        // Return composed data
        return [
            // 'vendors' => $data,
            'user' => $user,
            'order_ref' => $order->reference
        ];
    }
}
