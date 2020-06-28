<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorViewController extends Controller
{
    /**
     * Vendor dashboard index page
     */
    public function dashboard()
    {
        // Extract vendor ID
        $vendor_id = Auth::user()->id;

        // Fetch products
        $ofs_product = Product::where('vendor_id', $vendor_id)->where('quantity', '<', 10)->count();

        $ofs_product = ($ofs_product > 0);

        return view('vendor.dashboard', ['ofs_product' => $ofs_product]);
    }

    /**
     * Vendor Login Page
     */
    public function login()
    {
        return view('vendor.login');
    }

    /**
     * Products Page
     */
    public function products(Request $request)
    {
        $sort = $request->query('sort') ? true : false;
        return view('vendor.products', ['sort' => $sort]);
    }

    /**
     * Get all products
     * @return html
     */
    public function get_products()
    {
        // Extract vendor ID
        $vendor_id = Auth::user()->id;

        // Fetch products
        $products = Product::where('vendor_id', $vendor_id)->orderBy('created_at', 'desc')->get();

        // Return view
        return view('vendor.product.list', ['products' => $products]);
    }

    /**
     * Get update modals
     * @return html
     */
    public function product_update_modals()
    {
        // Extract vendor ID
        $vendor_id = Auth::user()->id;

        // Fetch products
        $products = Product::where('vendor_id', $vendor_id)->get();

        // Fetch categories
        $categories = Category::get();

        // Fetch subcategories
        $subcategories = SubCategory::orderBy('name')->get();

        $data = [
            'products' => $products,
            'categories' => $categories,
            'subcategories' => $subcategories
        ];

        // Return view
        return view('vendor.product.update', $data);
    }

    /**
     * Get view modals
     * @return html
     */
    public function product_view_modals()
    {
        // Extract vendor ID
        $vendor_id = Auth::user()->id;

        // Fetch products
        $products = Product::where('vendor_id', $vendor_id)->get();

        // Return view
        return view('vendor.product.view', ['products' => $products]);
    }

    /**
     * Get all categories
     * @return html
     */
    public function get_categories()
    {
        // Fetch categories
        $categories = Category::get();

        // Return view
        return view('vendor.product.categories', ['categories' => $categories]);
    }

    /**
     * Get all subcategories
     * @param string $category_id Base64 encoded category id
     * @return html
     */
    public function get_subcategories($category_id)
    {
        // Decode category id
        $category_id = base64_decode($category_id);

        // Fetch subcategories
        $subcategories = SubCategory::where('category_id', $category_id)->orderBy('name')->get();

        // Return view
        return view('vendor.product.subcategories', ['subcategories' => $subcategories]);
    }
}
