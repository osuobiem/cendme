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
    Route::post('signup', 'VendorController@create');

    // Vendor Login
    Route::post('login', 'VendorController@login');

    // Vendor Update
    Route::post('update/{id}', 'VendorController@update');
});
// END VENDOR ROUTES
