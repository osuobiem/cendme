<?php

namespace App\Http\Controllers;

use App\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Get all subcategories
     * @param string $category_id Base64 encoded category id
     * @return html
     */
    public function get($category_id)
    {
        // Decode category id
        $category_id = base64_decode($category_id);

        // Fetch subcategories
        $subcategories = SubCategory::where('category_id', $category_id)->orderBy('name')->get();

        // Return view
        return view('vendor.product.subcategories', ['subcategories' => $subcategories]);
    }
}
