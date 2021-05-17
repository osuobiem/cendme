<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        // Create new cart entry
        $cart = new Cart();

        // Assign values to new cart entry
        $cart->product_id = $product_id;
        $cart->user_id = $request->user()->id;
        $cart->quantity = 1;
        $cart->price = $product->price;

        // Try to save cart entry or catch error if any
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

    /**
     * Increase product quantity
     * @param int $product_id Product ID
     * @return json
     */
    public function plus(Request $request, $product_id)
    {
        $entry = Cart::where('product_id', $product_id)
            ->where('user_id', $request->user()->id)->first();

        // Check if product is in cart
        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ]);
        }

        $product = Product::find($product_id);

        // Check if product exists
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        // Check product available quantity
        if ($product->quantity < ($entry->quantity + 1)) {
            return response()->json([
                'success' => false,
                'message' => 'Only ' . $product->quantity . ' in stock'
            ]);
        }

        $entry->quantity += 1;
        $entry->price = $product->price * $entry->quantity;

        // Try to save cart entry or catch error if any
        try {
            $entry->save();

            // Compose success response
            return response()->json([
                'success' => true,
                'message' => 'Increment successful',
                'data' => [
                    'product' => [
                        'id' => $product->id,
                        'photo' => url('/') . Storage::url('products/' . $product->photo),
                        'title' => $product->title,
                        'price' => $entry->price,
                        'quantity' => $entry->quantity
                    ]
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
     * Decrement product quantity
     * @param int $product_id Product ID
     * @return json
     */
    public function minus(Request $request, $product_id)
    {
        $entry = Cart::where('product_id', $product_id)
            ->where('user_id', $request->user()->id)->first();

        // Check if product is in cart
        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ]);
        }

        $product = Product::find($product_id);

        // Check if product exists
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        // Check if entry quantity is 1
        if ($entry->quantity == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot go less than 1'
            ]);
        }

        $entry->quantity -= 1;
        $entry->price = $product->price * $entry->quantity;

        // Try to save cart entry or catch error if any
        try {
            $entry->save();

            // Compose success response
            return response()->json([
                'success' => true,
                'message' => 'Decrement successful',
                'data' => [
                    'product' => [
                        'id' => $product->id,
                        'photo' => url('/') . Storage::url('products/' . $product->photo),
                        'title' => $product->title,
                        'price' => $entry->price,
                        'quantity' => $entry->quantity
                    ]
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
     * Remove product from cart
     * @param int $product_id Product ID
     * @return json
     */
    public function remove(Request $request, $product_id)
    {
        $entry = Cart::where('product_id', $product_id)
            ->where('user_id', $request->user()->id)->first();

        // Check if product is in cart
        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ]);
        }

        // Remove entry from cart
        $entry->delete();

        // Return latest cart data
        return $this->list($request);
    }

    /**
     * List Products in cart
     * @return json
     */
    public function list(Request $request)
    {
        // Get user cart entries
        $entries = Cart::where('user_id', $request->user()->id)->get();

        // Check if cart is empty
        if (count($entries) < 1) {
            return response()->json([
                'success' => true,
                'message' => 'Cart is empty'
            ]);
        }

        $products = [];

        foreach ($entries as $entry) {
            $product = Product::find($entry->product_id);

            // Push composed entry to products array
            array_push($products, [
                'id' => $product->id,
                'photo' => url('/') . Storage::url('products/' . $product->photo),
                'title' => $product->title,
                'price' => $entry->price,
                'quantity' => $entry->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart Fetched',
            'data' => [
                'products' => $products
            ]
        ]);
    }
}
