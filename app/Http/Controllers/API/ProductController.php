<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\SubCategory;
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

    /**
     * Get Product Categories
     * @return json
     */
    public function categories()
    {
        // Get Categories
        $categories = Category::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Fetch Successful',
            'data' => [
                'categories' => $categories
            ]
        ]);
    }

    /**
     * Get Product SubCategories according to Category
     * @param int $category_id Category that Subcategory falls under
     * @return json
     */
    public function subcategories($category_id)
    {
        // Get Subcategories
        $subcategories = SubCategory::where('category_id', $category_id)->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Fetch Successful',
            'data' => [
                'subcategories' => $subcategories
            ]
        ]);
    }

    /**
     * Get Products by Vendor, Category and SubCategory
     * @param int $vendor_id Vendor ID
     * @param int $category_id Category ID
     * @param int $subcategory_id SubCategory ID
     * @return json
     */
    public function list($vendor_id, $category_id = false, $subcategory_id = false)
    {
        // Get Products by Category
        if ($category_id && !$subcategory_id) {
            $category = Category::find($category_id);
            $products = $category->product()
                ->where('vendor_id', $vendor_id)
                ->take(15)->get();
        }

        // Get Products by SubCategory
        elseif ($category_id && $subcategory_id) {
            $products = Product::where('vendor_id', $vendor_id)
                ->where('subcategory_id', $subcategory_id)
                ->take(15)->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Fetch Successful',
            'data' => [
                'products' => $products,
                'last_id' => count($products) > 0 ? $products[count($products) - 1]->id : null,
                'photo_url' => url('/') . Storage::url('products/')
            ]
        ]);
    }
}
