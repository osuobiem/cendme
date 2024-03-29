<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\Credential;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderVendor;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        // Try to add products to cart
        if (!$this->create_cart($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again'
            ]);
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
        $vendors = [];

        // Extract product data from cart list
        foreach ($list as $l) {
            $product = $l->product;

            array_push($products, [
                'product_id' => $product->id,
                'quantity' => $l->quantity,
                'price' => $l->price
            ]);

            $price_accumulator += $l->price;

            if (!in_array($product->vendor_id, $vendors)) {
                array_push($vendors, $product->vendor_id);
            }

            /* Push vendor addresses to the vendor address holder so they can be
                used for distance calculation*/

            if (!$current_v) {
                $current_v = $product->vendor->id;
                array_push($vendor_addresses, $product->vendor->address . ', ' . $product->vendor->area->name . ', ' . $product->vendor->area->state->name);
            } else {
                if ($current_v != $product->vendor->id) {
                    $current_v = $product->vendor->id;
                    array_push($vendor_addresses, $product->vendor->address . ', ' . $product->vendor->area->name . ', ' . $product->vendor->area->state->name);
                }
            }
        }

        // $distance = 0;

        /* Loop through vendor addresses and calculate probable product 
        purchase travel distance by shopper */
        // if (count($vendor_addresses) > 1) {
        //     foreach ($vendor_addresses as $key => $address) {
        //         $distance +=
        //             $key + 1 != count($vendor_addresses) ?
        //             $this->calculate_distance($address, $vendor_addresses[$key + 1])
        //             : $this->calculate_distance($address, $user->address . ', ' . $area->name . ', ' . $state->name);
        //     }
        // } else if (count($vendor_addresses) == 1) {
        //     $distance = $this->calculate_distance($vendor_addresses[0], $user->address . ', ' . $area->name . ', ' . $state->name);
        // } else {
        //     return ['success' => false, 'status' => 500, 'message' => 'User cart is empty'];
        // }

        // Convert m to km
        // $distance = $distance / 1000;

        // Calculate shopper transport fare
        // if(is_int($distance)) {
        //     $fare = ( ($distance-1)*100 ) + 200;
        // }
        // else {
        //     $fare = (((int) $distance) * 100) + 200;
        // }

        $fare = 500;
        
        // Total of transaction
        $total = ($price_accumulator * 1.1) + $fare;

        // Amount breakdown
        $amount = [
            "products" => $price_accumulator,
            "service_charge" => $price_accumulator * 0.1, // NOTE: Percentage value should retrieved from DB
            "shopper_transport_fare" => $fare,
            "total" => $total
        ];

        $order->amount = json_encode($amount);

        $ref = $this->generate_ref();
        $order->reference = $ref;
        $order->products = json_encode($request['products']);

        // Try to save order or catch error if any
        try {
            $order->save();

            foreach ($vendors as $v) {
                $order_v = new OrderVendor();
                $order_v->order_id = $order->id;
                $order_v->vendor_id = $v;

                $order_v->save();
            }

            foreach ($user->cart as $cart) {
                $cart->forceDelete();
            }

            return [
                'success' => true,
                'status' => 200,
                'message' => 'Order Created',
                'data' => [
                    "order" => [
                        'amount' => $amount,
                        'reference' => $ref
                    ],
                    "user" => $user
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

    /**
     * Add products to Cart
     * @param array $products Optional products parameter
     * @return json
     */
    public function create_cart(Request $request, $products = false)
    {
        if (!$products) {
            // Get validation rules
            $validate = $this->cart_rules($request);

            // Run validation
            if ($validate->fails()) {
                return false;
            }

            $products = $request['products'];
        }

        // Try to save cart entry or catch error if any
        try {
            Cart::where('user_id', $request->user()->id)->delete();

            foreach ($products as $product) {
                $p = Product::findOrFail($product['id']);

                $cart = new Cart();
                $cart->user_id = $request->user()->id;
                $cart->price = $p->price * $product['quantity'];
                $cart->quantity = $product['quantity'];
                $cart->product_id = $product['id'];

                $cart->save();
            }

            return true;
        } catch (\Throwable $th) {
            Log::error($th);
            Cart::where('user_id', $request->user()->id)->delete();

            return false;
        }
    }

    /**
     * Cart creation Validation Rules
     * @return object The validator object
     */
    private function cart_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|numeric',
            'products.*.quantity' => 'required|numeric'
        ]);
    }

    /**
     * Get Orders
     * @param string $id Order ID (Optional)
     * @return json
     */
    public function get(Request $request, $id = false)
    {
        // Check if order id was supplied
        if ($id) {
            // Get single order
            $order = Order::findOrFail($id);

            if (count(json_decode($order->products)) > 0 && $order->status == 'pending') {
                // Try to add products to cart
                if (!$this->create_cart($request, json_decode($order->products, true))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong. Please try again'
                    ]);
                }
            }

            $entries = $request->user()->cart;
            $cart = [];

            foreach ($entries as $entry) {
                $product = Product::find($entry->product_id);

                // Push composed entry to products array
                array_push($cart, [
                    'id' => $product->id,
                    'photo' => url('/') . Storage::url('products/' . $product->photo),
                    'title' => $product->title,
                    'price' => $entry->price,
                    'quantity' => $entry->quantity
                ]);
            }

            // Check if order has been accepted
            $shopper = [];
            if ($order->shopper) {
                $shopper = $order->shopper;
                $shopper->photo = url('/') . Storage::url('shoppers/' . $shopper->photo);
            }

            $updated_at = $order->updated_at;

            $time = date('Y-m-d g:i:s A');

            $updated_at = date_create($updated_at);
            $time = date_create($time);

            // updated_at time difference 
            $diff = date_diff($time, $updated_at)->i;

            // Check if order has not been accepted
            if ($diff > 30 && $order->status == 'paid') {

                if (!$this->cancel($request, $order->reference, true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Internal Server Error'
                    ], 500);
                }

                $order->status = 'cancelled';
            }

            $order->time_diff = $diff;

            return response()->json([
                'success' => true,
                'message' => 'Fetch Successful',
                'data' => [
                    'order' => $order,
                    'cart' => $cart,
                    'shopper' => $shopper
                ]
            ]);
        }

        // Get Orders
        $orders = $request->user()->orders()->orderByDesc('status')->orderByDesc('updated_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Fetch Successful',
            'data' => [
                'orders' => $orders
            ]
        ]);
    }

    /**
     * Delete Order
     * @param int $id Order ID
     * @return json
     */
    public function delete($id)
    {
        $order = Order::findOrFail($id);

        // Check order status
        if ($order->status != 'pending' && $order->status != 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be deleted'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order Deleted'
        ]);
    }

    /**
     * Cancel order
     * @param int $ref Order reference
     * @param bool $internal True if function is called by another function in this controller
     * @return json
     */
    public function cancel(Request $request, $ref, $internal = false)
    {
        $order = Order::where('reference', $ref)->firstOrFail();

        // Check if order has been canceled
        if ($order->status == 'cancelled') {
            return response()->json([
                'success' => true,
                'message' => 'Order Cancelled'
            ]);
        }

        // Check if order can be canceled
        if ($order->status != 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled'
            ]);
        }

        $amount = json_decode($order->amount);

        // Calculate refund amount
        $refund = $amount->total - $amount->payment_charge;

        // Add refund amount to user balance
        $user = $request->user();
        $user->balance += $refund;

        // Update order status
        $order->status = 'cancelled';

        $products = json_decode($order->products);

        try {
            if ($internal) {
                // Update user and order
                $user->save();
                $order->save();

                return true;
            }

            // Update product quantity
            foreach ($products as $product) {
                $p = Product::findOrFail($product->id);
                $p->quantity += $product->quantity;

                $p->save();
            }

            // Update user and order
            $user->save();
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order Cancelled',
                'data' => [
                    'order' => $order
                ]
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            if ($internal) {
                return false;
            }

            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * Accept Order Request
     * @param string $order_ref Order Reference
     * @return json
     */
    public function accept(Request $request, $order_ref)
    {
        $shopper = $request->user();

        $order = Order::where('reference', $order_ref)->firstOrFail();

        // Check Order status
        if ($order->status != 'paid' && $order->shopper_id != $shopper->id) {
            return response()->json([
                'success' => 'false',
                'message' => 'Order has either been cancelled by user or accepted by another shopper'
            ]);
        }

        $user = $order->user;

        // Confirm shopper eligibility
        // if ($shopper->area_id != $user->area_id || $shopper->balance < 5000) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You are not eligible to accept this request'
        //     ]);
        // }

        // Check if shopper has an accepted order
        // $a_order = Order::where('shopper_id', $shopper->id)
        //     ->where('status', 'accepted')
        //     ->orWhere('status', 'in transit')->first();
        // if ($a_order) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You already have a pending order'
        //     ]);
        // }

        $order->shopper_id = $shopper->id;
        $order->status = 'accepted';

        $data = [];
        $vendors = [];

        // Get products in order list
        $products = json_decode($order->products);

        foreach ($products as $product) {
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

        $user->photo = url('/') . Storage::url('users/' . $user->photo);
        // $shopper->free = false;
        $amount = json_decode($order->amount);
        $shopper->balance += $amount->products;

        try {
            // Update order data
            $order->save();

            $shopper->save();

            return response()->json([
                'success' => true,
                'message' => 'Order Accepted',
                'data' => [
                    'vendors' => $data,
                    'user' => $user,
                    'order_ref' => $order->reference
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
     * Get order data after notification has been sent to shopper
     * @param string $order_ref Order Reference
     * @return json
     */
    public function get_from_notification($order_ref)
    {
        $order = Order::where('reference', $order_ref)->firstOrFail();

        $user = $order->user;

        $data = [];
        $vendors = [];

        // Get products in order list
        $products = json_decode($order->products);

        foreach ($products as $product) {
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
                $vendors[$vendor->id]["products_total"] += $p->price * $product->quantity;
            } else {
                $v = [
                    "id" => $vendor->id,
                    "name" => $vendor->business_name,
                    "phone" => $vendor->phone,
                    "address" => $vendor->address,
                    "photo" => url('/') . Storage::url('vendors/' . $vendor->photo),
                    "products" => [],
                    "products_total" => $p->price
                ];
                array_push($v["products"], $p_data);

                $vendors[$vendor->id] = $v;
            }
        }

        // Compose response data
        foreach ($vendors as $vendor) {
            array_push($data, $vendor);
        }

        $user->photo = url('/') . Storage::url('users/' . $user->photo);

        return response()->json([
            'success' => true,
            'message' => 'Order Fetch Successful',
            'data' => [
                'vendors' => $data,
                'user' => $user,
                'order_ref' => $order->reference
            ]
        ]);
    }

    /**
     * Update continue wait field
     * @param string $order_ref Order Reference
     * 
     * @return json
     */
    public function continue_wait($order_ref)
    {
        $order = Order::where('reference', $order_ref)->firstOrFail();

        $order->continue_wait = 1;
        $u = $order->updated_at;
        try {
            $order->updated_at = $u;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Update Successful',
                'data' => [
                    'order' => $order
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
}
