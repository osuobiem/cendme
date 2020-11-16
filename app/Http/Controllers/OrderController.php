<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderVendor;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Get Vendor Orders
     * @return object
     */
    public function get($limit = false)
    {
        $vendor = Auth::user();

        $v_orders = $vendor->v_orders();
        $v_orders = !$limit ? $v_orders->orderBy('updated_at', 'desc')->get() : $v_orders->limit($limit)->orderBy('updated_at', 'desc')->get();

        $orders = [];

        if ($v_orders) {
            foreach ($v_orders as $vo) {
                array_push($orders, [
                    'id' => $vo->order->id,
                    'ref' => $vo->order->reference,
                    'date' => $vo->order->created_at,
                    'status' => $vo->status
                ]);
            }
        }

        return view('vendor.order.list', ['orders' => $orders]);
    }

    /**
     * Get Vendor Order Views
     * @return object
     */
    public function get_views($limit = false)
    {
        $vendor = Auth::user();

        $v_orders = $vendor->v_orders();
        $v_orders = !$limit ? $v_orders->orderBy('updated_at', 'desc')->get() : $v_orders->orderBy('updated_at', 'desc')->limit($limit)->get();

        $orders = [];

        if ($v_orders) {
            foreach ($v_orders as $vo) {
                $products = [];
                $products_total = 0;

                $p = json_decode($vo->order->products);
                $p = count($p) > 0 ? $p : json_decode($vo->order->paid_for);

                $c_data = $this->compose_order_data($p, $vendor);
                $products = $c_data['products'];
                $products_total = $c_data['total'];
                if (count($products) < 1) {
                    $p = json_decode($vo->order->paid_for);
                    $c_data = $this->compose_order_data($p, $vendor);
                    $products = $c_data['products'];
                    $products_total = $c_data['total'];
                }

                array_push($orders, [
                    'id' => $vo->order->id,
                    'ref' => $vo->order->reference,
                    'date' => $vo->order->created_at,
                    'status' => $vo->status,
                    'products' => $products,
                    'products_total' => $products_total
                ]);
            }
        }

        return view('vendor.order.view', ['orders' => $orders]);
    }

    /**
     * Compose Order Data
     * @param array $p
     * @param object $vendor
     * 
     * @return array
     */
    public function compose_order_data($p, $vendor)
    {
        $products = [];
        $products_total = 0;

        foreach ($p as $product) {
            $p_data = Product::find($product->id);
            if ($p_data->vendor_id == $vendor->id) {
                $price = $p_data->price * $product->quantity;
                $products_total += $price;

                array_push($products, [
                    'title' => $p_data->title,
                    'quantity' => $product->quantity,
                    'price' =>  $price
                ]);
            }
        }

        return ['products' => $products, 'total' => $products_total];
    }

    /**
     * Confirm Order Products Payment
     * @param int $order_id Order ID
     * 
     * @return json
     */
    public function confirm_payment($order_id)
    {
        $order = Order::findOrFail($order_id);
        $vendor = Auth::user();

        $paid_list = [];
        $unpaid_list = [];

        // Get products
        $products = json_decode($order->products);
        foreach ($products as $product) {
            $p = Product::findOrFail($product->id);

            // Check if vendor owns product
            if ($p->vendor_id == $vendor->id) {
                array_push($paid_list, $product);
            } else {
                array_push($unpaid_list, $product);
            }
        }
        $pf = json_decode($order->paid_for, true);
        $pf = $pf ? $pf : [];
        $paid_list = array_merge($paid_list, $pf);

        if (count($unpaid_list) < 1) {
            $order->status = 'in transit';
        }
        $order->products = json_encode($unpaid_list);
        $order->paid_for = json_encode($paid_list);

        $order_vendor = OrderVendor::where('order_id', $order->id)->where('vendor_id', $vendor->id)->first();
        $order_vendor->status = 1;

        // Try Save
        try {
            $order->save();
            $order_vendor->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment Confirmed'
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
