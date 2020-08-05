<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Get all products at random accprding to vendor
     * @param int $vendor_id Vendor that products belongs to
     * @return json
     */
    public function list_random($vendor_id)
    {
        // Get products
        $products = Product::where('vendor_id', $vendor_id)
            ->orderBy('updated_at', 'desc')
            ->take(15)->get();

        return response()->json([
            'success' => true,
            'message' => 'Fetch Successful',
            'data' => [
                'products' => $products,
                'last_id' => $products[count($products) - 1]->id,
                'photo_url' => url('/') . Storage::url('products/')
            ]
        ]);
    }
}
