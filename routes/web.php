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

    // Vendor Signup
    Route::post('signup', 'VendorController@signup');

    // Vendor Login
    Route::post('login', 'VendorController@login');

    Route::group(['middleware' => 'auth:vendors'], function () {

        // Vendor Update
        Route::post('update/{id}', 'VendorController@update');
    });
});
// ---------------

/**
 * PRODUCT ROUTES
 */
Route::group(['prefix' => 'product', 'middleware' => 'auth:vendors'], function () {

    // Create Product
    Route::post('create/{vendor_id}', 'ProductController@create');

    // Update Product
    Route::post('update/{id}', 'ProductController@update');

    // Delete Product
    Route::delete('delete/{id}', 'ProductController@delete');
});
// ---------------
