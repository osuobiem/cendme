<?php

namespace App\Http\Controllers\API;

use App\Credential;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        // $order->save();

        $products = [];
        $p_price = 0;
        $vendor_addresses = [];
        $current_v = false;

        foreach ($request['products'] as $product) {
            array_push($products, new OrderProduct([
                'product_id' => $product['id'],
                'quantity' => $product['quantity']
            ]));

            $prod = Product::find($product['id']);
            $p_price += ($prod->price * $product['quantity']);

            if (!$current_v) {
                $current_v = $prod->vendor->id;
                array_push($vendor_addresses, $prod->vendor->address);
            } else {
                if ($current_v != $prod->vendor->id) {
                    $current_v = $prod->vendor->id;
                    array_push($vendor_addresses, $prod->vendor->address);
                }
            }
        }

        $distance = $this->calculate_distance($request->user()->address, $vendor_addresses);
        dd($distance);

        $price = [
            "products" => $p_price,
            "service charge" => $p_price * 0.1,
            "total" => $p_price + ($p_price * 0.1)
        ];
        dd($price);
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

    public function calculate_distance($user_address, $vendor_addresses)
    {
        // Retrieve necessary credentials
        $api_key = Credential::where('key', 'google_api_key')->first();

        if (count($vendor_addresses) > 1) {
            $distance = 0;

            $origin = $vendor_addresses[0];
            array_shift($vendor_addresses);

            $destinations = '';
            foreach ($vendor_addresses as $key => $address) {
                if (count($vendor_addresses) == 1) {
                    $destinations = $address;
                } else {
                    $destinations +=
                        $key + 1 == count($vendor_addresses) ? $address : $address . '|';
                }
            }

            $response = Http::get(
                'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $origin . '&destinations=' . $destinations . '&key=' . $api_key
            );
            dd($response->json());
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
            'products' => 'required'
        ]);
    }
    // --------------
}
