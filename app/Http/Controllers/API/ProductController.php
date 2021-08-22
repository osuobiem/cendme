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
     * Get all products at random according to vendor
     * @param int $vendor_id Vendor that products belongs to
     * @return json
     */
    public function list_random($vendor_id)
    {
        // Get products
        $products = Product::where('vendor_id', $vendor_id)
            ->where('quantity', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->take(5)->get();

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
    public function list($vendor_id, $category_id = false, $subcategory_id = false, $paginate = false, $last_id = false)
    {
        $products = Product::where('vendor_id', $vendor_id)->where('quantity', '>', 0);

        // Get Products by Category
        if ($category_id && $category_id != 'paginate') {
            $category = Category::find($category_id);
            $products = $category->product()
                ->where('quantity', '>', 0)
                ->where('vendor_id', $vendor_id);
        } elseif ($category_id == 'paginate') {
            $products = $products->where('id', '>', $subcategory_id)->take(5)->get();
            return $this->return_products($products);
        }

        // Get Products by SubCategory
        if ($category_id != 'paginate' && $subcategory_id && $subcategory_id != 'paginate') {
            $products = Product::where('vendor_id', $vendor_id)->where('quantity', '>', 0)->where('subcategory_id', $subcategory_id);
        } elseif ($subcategory_id == 'paginate') {
            $products = Product::where('vendor_id', $vendor_id)->where('quantity', '>', 0)->where('id', '>', $paginate)->take(5)->get();
            return $this->return_products($products);
        }

        // Check for pagination
        if ($paginate == 'paginate') {
            $products = $products->where('id', '>', $last_id)->take(5)->get();
            return $this->return_products($products);
        }

        $products = $products->take(5)->get();
        return $this->return_products($products);
    }

    /**
     * Return fetched products
     * @param $products
     * @return json
     */
    function return_products($products)
    {
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


    /**
     * Search for products
     * @param int $vendor_id Vendor ID
     * @param string $keywork Search keyword
     * @return json
     */
    public function search($vendor_id, $keyword)
    {
        // Search for products
        
        $results = Product::where('vendor_id', $vendor_id)
            ->where('quantity', '>', 0)
            ->where('title', 'LIKE', '%' . $keyword . '%')->get();
            $results = Product::paginate(12);
        return response()->json([
            'success' => true,
            'message' => 'Search Successful',
            'data' => [
                'products' => $results,
                'photo_url' => url('/') . Storage::url('products/')
            ]
        ]);
    }
}
