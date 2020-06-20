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

    Route::group(['middleware' => 'auth'], function () {

        // Vendor Update
        Route::post('update/{id}', 'VendorController@update');

        // Vendor Logout
        Route::get('logout', 'VendorController@logout');
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
