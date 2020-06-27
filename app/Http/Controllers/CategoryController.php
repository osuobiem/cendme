<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     * @return html
     */
    public function get()
    {
        // Fetch categories
        $categories = Category::get();

        // Return view
        return view('vendor.product.categories', ['categories' => $categories]);
    }
}
