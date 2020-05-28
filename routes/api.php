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

/**
 * USER ROUTES
 */
Route::group(['prefix' => 'user'], function () {

    // User Signup
    Route::post('signup', 'UserController@create');

    // User Login
    Route::post('login', 'UserController@login');

    // User Update
    Route::post('update/{id}', 'UserController@update');
});
// END USER ROUTES

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
