<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Get Vendor Orders
     * @return object
     */
    public function get($limit = false) {
        $vendor = Auth::user();

        $v_orders = $vendor->v_orders();
        $v_orders = !$limit ? $v_orders->get() : $v_orders->limit($limit)->get();

        $orders = [];

        if($v_orders) {
            foreach($v_orders as $vo) {
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
}
