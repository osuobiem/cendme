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
 * MAIN ROUTES
 */
// Home/Landing Page
Route::get('', 'HomeController@index');
// -------------

/**
 * VENDOR ROUTES
 */
Route::group(['prefix' => '/vendor'], function () {

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

        // Vendor Signup Page
        Route::get('sign-up', 'VendorViewController@sign_up');

        // Get Lgas by State
        Route::get('lgas/{state_id}/{logged?}', 'VendorViewController@get_lgas');
        // -----------
    });

    Route::group(['middleware' => 'auth'], function () {

        // LOGIC ----
        // Vendor Update
        Route::post('update/{id}', 'VendorController@update');

        // Update Vendor Photo
        Route::post('update-photo/{id}', 'VendorController@update_photo');

        // Vendor Logout
        Route::get('logout', 'VendorController@logout');

        // Update Bank Details
        Route::post('update-bank-details/{id}', 'VendorController@update_bank_details');

        // Withdraw
        Route::post('withdraw', 'VendorController@withdraw');
        // ----------

        // UI ------
        // Vendor Dashboard Page
        Route::get('', 'VendorViewController@dashboard');

        // Products Page
        Route::get('products', 'VendorViewController@products');

        // Orders Page
        Route::get('orders', 'VendorViewController@orders');

        // My Account Page
        Route::get('account', 'VendorViewController@account');

        // My Wallet Page
        Route::get('wallet', 'VendorViewController@wallet');

        // Get all Products
        Route::get('products/get', 'VendorViewController@get_products');

        // Get product update modals
        Route::get('products/update-modals', 'VendorViewController@product_update_modals');

        // Get product view modals
        Route::get('products/add-form', 'VendorViewController@product_add_form');

        // Get product view modals
        Route::get('products/view-modals', 'VendorViewController@product_view_modals');

        // Get all Categories
        Route::get('categories', 'VendorViewController@get_categories');

        // Get SubCategories by Category
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

/**
 * ADMIN ROUTES
 */
Route::group(['prefix' => '/admin'], function () {

    Route::group(['middleware' => 'guest:admins'], function () {

        // LOGIC -------

        // Admin Login
        Route::post('p-login', 'AdminController@login');
        // ------------


        // UI -------
        // Admin Login Page
        Route::get('login', 'AdminViewController@login');
        // -----------
    });

    Route::group(['middleware' => 'auth:admins'], function () {

        // LOGIC ----
        // Admin Logout
        Route::get('logout', 'AdminController@logout');

        // Admin Update
        Route::post('update', 'AdminController@update');

        // Delete Vendor
        Route::delete('vendors/delete/{id}', 'AdminController@delete_vendor');

        // Delete Agent
        Route::delete('agents/delete/{id}', 'AdminController@delete_agent');

        // Delete User
        Route::delete('users/delete/{id}', 'AdminController@delete_user');
        // --------------


        // UI ------
        // Admin Dashboard Page
        Route::get('', 'AdminViewController@dashboard');

        // Vendors Page
        Route::get('vendors', 'AdminViewController@vendors');

        // Agents Page
        Route::get('shoppers', 'AdminViewController@agents');

        // Users Page
        Route::get('users', 'AdminViewController@users');

        // Account Page
        Route::get('account', 'AdminViewController@account');

        // Credential Settings Page
        Route::get('settings/credentials', 'AdminViewController@credential_settings');

        // Get all Vendors
        Route::get('vendors/get/{limit?}', 'AdminViewController@get_vendors');

        // Get all Agents
        Route::get('agents/get/{limit?}', 'AdminViewController@get_agents');

        // Get all Users
        Route::get('users/get/{limit?}', 'AdminViewController@get_users');

        // Get vendor view modals
        Route::get('vendors/view-modals', 'AdminViewController@vendor_view_modals');

        // Get agent view modals
        Route::get('agents/view-modals', 'AdminViewController@agent_view_modals');

        // Get user view modals
        Route::get('users/view-modals', 'AdminViewController@user_view_modals');
        // -------------
    });
});
// ---------------
