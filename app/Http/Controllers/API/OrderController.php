<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // CREATE ORDER
    /**
     * Create an order
     * @return json
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

        // Store order data
        $store = $this->cstore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process order creation
     * @return array
     */
    public function cstore(Request $request)
    {
        // New order object
        $order = new Order();

        // Assign order object properties
        $order->user_id = $request->user()->id;
        $order->amount = json_encode($request['amount']);
        $order->expires_at = date('Y-m-d H:i:s', time() + 18000);

        $order->save();

        $products = [];
        foreach ($request['products'] as $key => $product) {
            array_push($products, new OrderProduct([
                'product_id' => $product[0],
                'quantity' => $product[1]
            ]));
        }

        $order->order_product()->saveMany($products);
        dd("R");

        // Try user save or catch error if any
        try {
            //  $user->save();

            //  // Attempt auto login
            //  $login = $this->fast_login($request);
            //  if ($login) {

            //      // Send success response
            //      return [
            //          'success' => true,
            //          'status' => 200,
            //          'message' => 'Signup Successful',
            //          'data' => $login
            //      ];
            //  } else {
            //      return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            //  }
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Order Creation Validation Rules
     * @return object The validator object
     */
    private function create_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'amount' => 'required',
            'products' => 'required'
        ]);
    }
    // --------------
}
