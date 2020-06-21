<?php

namespace App\Http\Controllers\API;

use App\Credential;
use App\Http\Controllers\Controller;
use App\Lga;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        $user = User::find($request->user()->id);

        // Get LGA and state to be appended to addresses
        $lga_ob = $user->lga;
        $lga = $lga_ob->name;
        $state = Lga::find($lga_ob->id)->state->name;

        $products = [];
        $p_price = 0;
        $vendor_addresses = [];
        $current_v = false;

        // Extract product data from input
        foreach ($request['products'] as $product) {

            // Create and push new OrderProduct(pivot data) object
            array_push($products, new OrderProduct([
                'product_id' => $product['id'],
                'quantity' => $product['quantity']
            ]));

            $prod = Product::find($product['id']);

            // Calculate product price with respect to quantity
            $p_price += ($prod->price * $product['quantity']);

            /* Push vendor addresses to the vendor address holder so they can be
                used for distance calculation*/
            if (!$current_v) {
                $current_v = $prod->vendor->id;
                array_push($vendor_addresses, $prod->vendor->address . ', ' . $lga . ', ' . $state);
            } else {
                if ($current_v != $prod->vendor->id) {
                    $current_v = $prod->vendor->id;
                    array_push($vendor_addresses, $prod->vendor->address . ', ' . $lga . ', ' . $state);
                }
            }
        }

        $distance = 0;

        /* Loop through vendor addresses and calculate probable product 
        purchase travel distance by agent */
        if (count($vendor_addresses) > 1) {
            foreach ($vendor_addresses as $key => $address) {
                $distance +=
                    $key + 1 != count($vendor_addresses) ?
                    $this->calculate_distance($address, $vendor_addresses[$key + 1])
                    : $this->calculate_distance($address, $user->address . ', ' . $lga . ', ' . $state);
            }
        } else {
            $distance = $this->calculate_distance($vendor_addresses[0], $user->address . ', ' . $lga . ', ' . $state);
        }

        // Convert m to km
        $distance = $distance / 1000;

        // Calculate agent transport fare
        $fare = is_int($distance) ? $distance * 200 : ($distance + 1) * 200; // NOTE: Fare default should be retrieved from DB

        // Multiply fare by 2 for to and fro travel
        $fare *= 2;

        // Amount breakdown
        $amount = [
            "products" => $p_price,
            "service_charge" => $p_price * 0.1, // NOTE: Percentage value should retrieved from DB
            "agent_transport_fare" => $fare,
            "total" => $p_price + ($p_price * 0.1) + $fare
        ];

        $order->amount = json_encode($amount);

        // Try to save order or catch error if any
        try {
            $order->save();
            $order->order_product()->saveMany($products);

            return [
                'success' => true,
                'status' => 200,
                'message' => 'Order Created',
                'data' => [
                    "amount" => $amount
                ]
            ];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Calculate distance between two addresses
     * @param string $origin Origin address/starting point
     * @param string $destination Destination address/end opoint
     * @return integer Distance in meters
     */
    public function calculate_distance($origin, $destination)
    {
        // Retrieve necessary credentials
        $api_key = Credential::where('key', 'google_api_key')->first()->value;

        // Ping google distance matrix api to retrieve distance
        $response = Http::get(
            'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins='
                . $origin . '&destinations=' . $destination . '&key=' . $api_key
        );
        $resp = $response->json();

        // Extract and return distance from response
        return $resp['rows'][0]['elements'][0]['distance']['value'];
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
