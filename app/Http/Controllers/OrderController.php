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
    public function get() {
        $vendor = Auth::user();

        $v_orders = $vendor->v_orders;
        $orders = [];

        if($v_orders) {
            foreach($v_orders as $vo) {
                array_push($orders, $vo->order);
            }
        }

        dd($orders);
    }
}
