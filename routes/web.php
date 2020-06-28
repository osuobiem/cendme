<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * VENDOR ROUTES
 */
Route::group(['prefix' => 'vendor'], function () {

    Route::group(['middleware' => 'guest:vendors'], function () {

        // LOGIC -------
        // Vendor Signup
        Route::post('p-signup', 'VendorController@signup');

        // Vendor Login
        Route::post('p-login', 'VendorController@login');
        // ------------


        // UI -------
        // Vendor Login Page
        Route::get('login', 'VendorViewController@login');
        // -----------
    });

    Route::group(['middleware' => 'auth'], function () {

        // LOGIC ----
        // Vendor Update
        Route::post('update/{id}', 'VendorController@update');

        // Vendor Logout
        Route::get('logout', 'VendorController@logout');
        // ----------

        // UI ------
        // Vendor Dashboard Page
        Route::get('', 'VendorViewController@dashboard');

        // Products Page
        Route::get('products', 'VendorViewController@products');

        // Orders Page
        Route::get('orders', 'VendorViewController@orders');

        // Get all Products
        Route::get('products/get', 'VendorViewController@get_products');

        // Get product update modals
        Route::get('products/update-modals', 'VendorViewController@product_update_modals');

        // Get product view modals
        Route::get('products/view-modals', 'VendorViewController@product_view_modals');

        // Get all Categories
        Route::get('categories', 'VendorViewController@get_categories');

        // Get all SubCategories
        Route::get('subcategories/{category_id}', 'VendorViewController@get_subcategories');
        // --------
    });
});
// ---------------

/**
 * PRODUCT ROUTES
 */
Route::group(['prefix' => 'product', 'middleware' => 'auth'], function () {

    // Create Product
    Route::post('create', 'ProductController@create');

    // Update Product
    Route::post('update/{id}', 'ProductController@update');

    // Change Product Status
    Route::get('status/{id}', 'ProductController@status');

    // Delete Product
    Route::delete('delete/{id}', 'ProductController@delete');
});
// ---------------
