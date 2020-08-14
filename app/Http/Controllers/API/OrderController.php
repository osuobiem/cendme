<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\Credential;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // String of English letters
    private $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // CREATE ORDER
    /**
     * Create an order
     * @return json
     */
    public function create(Request $request)
    {
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

        $user = $request->user();

        // Get area and state to be appended to addresses
        $area = $user->area;
        $state = $area->state;

        // Get product list from cart
        $list = Cart::where('user_id', $user->id)->get();


        $products = [];
        $price_accumulator = 0;
        $vendor_addresses = [];
        $current_v = false;

        // Extract product data from cart list
        foreach ($list as $l) {
            $product = $l->product;

            array_push($products, [
                'product_id' => $product->id,
                'quantity' => $l->quantity,
                'price' => $l->price
            ]);

            $price_accumulator += $l->price;

            $prod = Product::find($product['id']);


            /* Push vendor addresses to the vendor address holder so they can be
                used for distance calculation*/
            if (!$current_v) {
                $current_v = $prod->vendor->id;
                array_push($vendor_addresses, $prod->vendor->address . ', ' . $prod->vendor->area->name . ', ' . $prod->vendor->area->state->name);
            } else {
                if ($current_v != $prod->vendor->id) {
                    $current_v = $prod->vendor->id;
                    array_push($vendor_addresses, $prod->vendor->address . ', ' . $prod->vendor->area->name . ', ' . $prod->vendor->area->state->name);
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
                    : $this->calculate_distance($address, $user->address . ', ' . $area->name . ', ' . $state->name);
            }
        } else {
            $distance = $this->calculate_distance($vendor_addresses[0], $user->address . ', ' . $area->name . ', ' . $state->name);
        }

        // Convert m to km
        $distance = $distance / 1000;

        // Calculate agent transport fare
        $fare = is_int($distance) ? $distance * 200 : (((int) $distance) + 1) * 200; // NOTE: Fare default should be retrieved from DB

        // Multiply fare by 2 for to and fro travel
        // $fare *= 2;

        // Amount breakdown
        $amount = [
            "products" => $price_accumulator,
            "service_charge" => $price_accumulator * 0.1, // NOTE: Percentage value should retrieved from DB
            "agent_transport_fare" => $fare,
            "total" => $price_accumulator + ($price_accumulator * 0.1) + $fare
        ];

        $order->amount = json_encode($amount);

        $ref = $this->generate_ref();
        $order->reference = $ref;

        // Try to save order or catch error if any
        try {
            $order->save();

            return [
                'success' => true,
                'status' => 200,
                'message' => 'Order Created',
                'data' => [
                    "order" => [
                        'amount' => $amount,
                        'reference' => $ref
                    ]
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
     * Generate unique order reference
     * @return string
     */
    public function generate_ref()
    {
        $seg1 = substr(str_shuffle($this->alpha), 0, 10);
        $seg2 = date('YmdHis');

        return 'ORDER-' . $seg1 . '-' . $seg2;
    }
}
