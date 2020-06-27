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

    // Get all Products
    Route::get('get', 'ProductController@get');

    // Get update modals
    Route::get('get-update-modals', 'ProductController@get_update_modals');
});
// ---------------

/**
 * CATEGORY ROUTES
 */
Route::group(['prefix' => 'category', 'middleware' => 'auth'], function () {

    // Get all Categories
    Route::get('get', 'CategoryController@get');
});
// ---------------

/**
 * SUBCATEGORY ROUTES
 */
Route::group(['prefix' => 'subcategory', 'middleware' => 'auth'], function () {

    // Get all SubCategories
    Route::get('get/{category_id}', 'SubCategoryController@get');
});
// ---------------
