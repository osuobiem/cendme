<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Add product to Cart
     * @param int $product_id Product ID
     * @return json
     */
    public function add(Request $request, $product_id)
    {
        // Check if product has already been added to cart
        $check_cart = Cart::where('product_id', $product_id)
            ->where('user_id', $request->user()->id)
            ->count();

        if ($check_cart > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Product already added to cart'
            ]);
        }

        // Check if product exists
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        // Check if product is out of stock
        if ($product->quantity < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock'
            ]);
        }

        // Create new cart item
        $cart = new Cart();

        // Assign values to new cart item
        $cart->product_id = $product_id;
        $cart->user_id = $request->user()->id;
        $cart->quantity = 1;
        $cart->price = $product->price;

        // Try cart item save or catch error if any
        try {
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart'
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
