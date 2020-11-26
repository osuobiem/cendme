<?php

namespace App\Http\Controllers;

use App\Area;
use App\Bank;
use App\Category;
use App\Product;
use App\State;
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
     * Vendor Sign up Page
     */
    public function sign_up()
    {
        // Get states
        $states = State::orderBy('name', 'asc')->get();

        return view('vendor.sign-up', ['states' => $states]);
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
     * Orders Page
     */
    public function orders()
    {
        return view('vendor.order.index');
    }

    /**
     * My Wallet Page
     */
    public function wallet()
    {
        return view('vendor.wallet');
    }

    /**
     * My Account Page
     */
    public function account()
    {
        // Extract vendor object
        $vendor = Auth::user();

        // Get state id from area object
        $state_id = $vendor->area->state_id;

        // Get states and areas
        $states = State::orderBy('name', 'asc')->get();
        $areas = Area::where('state_id', $state_id)->orderBy('name', 'asc')->get();

        // Get Banks
        $banks = Bank::orderBy('name', 'asc')->get();

        // Compose view data
        $data = [
            'vendor' => $vendor,
            'states' => $states,
            'areas' => $areas,
            'banks' => $banks,
            'account' => $vendor->account
        ];

        return view('vendor.account', $data);
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
     * Return product add form
     */
    public function product_add_form()
    {
        return view('vendor.product.add');
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

        $data = [
            'products' => $products,
            'categories' => $categories
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
     * Get subcategories by category
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

    /**
     * Get local government areas by state
     * @param string $state_id Base64 encoded state id
     * @return html
     */
    public function get_areas($state_id, $notlogged = false)
    {
        // Decode state id
        $state_id = base64_decode($state_id);

        // Fetch areas
        $areas = Area::where('state_id', $state_id)->orderBy('name')->get();

        // Return view
        if (!$notlogged) {
            return view('vendor.account.areas', ['areas' => $areas]);
        } else {
            return view('vendor.areas', ['areas' => $areas]);
        }
    }
}
