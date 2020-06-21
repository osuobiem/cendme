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
        Route::post('signup', 'UserController@create');

        // User Login
        Route::post('login', 'UserController@login');

        Route::group(['middleware' => 'auth:users-api'], function () {

            // User Update
            Route::post('update/{id}', 'UserController@update');
        });
    });
    // ----------------

    /**
     * AGENT ROUTES
     */
    Route::group(['prefix' => 'agent'], function () {

        // Agent Signup
        Route::post('signup', 'AgentController@create');

        // Agent Login
        Route::post('login', 'AgentController@login');

        Route::group(['middleware' => 'auth:agents-api'], function () {

            // Agent Update (after verification)
            Route::post('update/{id}', 'AgentController@update');

            // Agent Update (before verification)
            Route::post('update/{id}/before', 'AgentController@update_b');

            // Verify agent using BVN
            Route::get('verify/{id}', 'AgentController@verify');
        });
    });
    // ----------------

    /**
     * ORDER ROUTES
     */
    Route::group(['prefix' => 'order', 'middleware' => ['auth:users-api']], function () {
        Route::post('create', 'OrderController@create');
    });
    // ----------------
});
