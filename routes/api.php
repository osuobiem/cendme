<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'API'], function () {

    /**
     * USER ROUTES
     */
    Route::group(['prefix' => 'user'], function () {

        // User Signup
        Route::post('sign-up', 'UserController@create');

        // User Login
        Route::post('login', 'UserController@login');

        Route::group(['middleware' => 'auth:users-api'], function () {

            // User Update
            Route::post('update', 'UserController@update');

            // Update Photo
            Route::post('update-photo', 'UserController@update_photo');

            // Get Paystack Payment Credentials
            Route::get('payment/credentials', 'AuthController@get_paystack');

            // Initialize Payment
            Route::post('transaction/finalize', 'AuthController@send_request_notification');
        });
    });
    // ----------------

    /**
     * SHOPPER ROUTES
     */
    Route::group(['prefix' => 'shopper'], function () {

        // Shopper Signup
        Route::post('signup', 'ShopperController@create');

        // Shopper Login
        Route::post('login', 'ShopperController@login');

        Route::group(['middleware' => 'auth:shoppers-api'], function () {

            // Shopper Update (after verification)
            Route::post('update', 'ShopperController@update');

            // Shopper Update (before verification)
            Route::post('update/before', 'ShopperController@update_b');

            // Update Photo
            Route::post('update-photo', 'ShopperController@update_photo');

            // Get Paystack Payment Credentials
            Route::get('payment/credentials', 'AuthController@get_paystack');

            // Initialize Payment
            Route::post('transaction/finalize', 'AuthController@finalize');
        });
    });
    // ----------------

    /**
     * ORDER ROUTES
     */
    Route::group(['prefix' => 'order', 'middleware' => ['auth:users-api']], function () {
        // Create Order
        Route::post('create', 'OrderController@create');

        // Get Orders
        Route::get('get/{id?}', 'OrderController@get');

        // Delete Order
        Route::get('delete/{id}', 'OrderController@delete');
    });
    // ----------------

    /**
     * PRODUCT ROUTES
     */
    Route::group(['prefix' => 'products', 'middleware' => ['auth:users-api']], function () {

        // Get all products according to vendor
        Route::get('all/{vendor_id}', 'ProductController@list_random');

        // Get all products according to vendor, category and subcategory
        Route::get('all/{vendor_id}/{category_id?}/{subcategory_id?}', 'ProductController@list');

        // Search for vendor products
        Route::get('search/{vendor_id}/{keyword}', 'ProductController@search');
    });
    // -------------

    /**
     * CART ROUTES
     */
    Route::group(['prefix' => 'cart', 'middleware' => ['auth:users-api']], function () {
        // Add product to cart
        Route::get('add/{product_id}', 'CartController@add');

        // Increase product quantity in cart
        Route::get('plus/{product_id}', 'CartController@plus');

        // Decrease product quantity in cart
        Route::get('minus/{product_id}', 'CartController@minus');

        // Remove product from cart
        Route::get('remove/{product_id}', 'CartController@remove');

        // List Cart Entries
        Route::get('view', 'CartController@list');
    });
    // -------------

    /**
     * GENERIC ROUTES
     */
    // Get all states
    Route::get('states', 'StateController@list');

    // Get all areas according to state
    Route::get('areas/{state_id}', 'AreaController@list');

    // PROTECTED ROUTES
    Route::group(['middleware' => ['auth:users-api']], function () {

        // Get all vendors according to area
        Route::get('vendors/{area_id}', 'VendorController@list');

        // Get product categories
        Route::get('categories', 'ProductController@categories');

        // Get product subcategories according to category
        Route::get('subcategories/{category_id}', 'ProductController@subcategories');
    });
    // --------------
});
