<?php

namespace App\Http\Controllers;

use App\Shopper;
use App\ShopperVendor;
use App\User;
use App\Vendor;
use Illuminate\Support\Facades\Auth;

class AdminViewController extends Controller
{
    /**
     * Admin Login Page
     */
    public function login()
    {
        return view('admin.login');
    }

    /**
     * Admin dashboard index page
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Vendors page
     */
    public function vendors()
    {
        return view('admin.vendor.index');
    }

    /**
     * Agents page
     */
    public function agents()
    {
        return view('admin.agent.index');
    }

    /**
     * Users page
     */
    public function users()
    {
        return view('admin.user.index');
    }

    /**
     * Account page
     */
    public function account()
    {
        $admin = Auth::guard('admins')->user();

        return view('admin.account', ['admin' => $admin]);
    }

    /**
     * Settings page
     */
    public function settings($page)
    {
        return view('admin.settings.index', ['page' => $page]);
    }

    /**
     * Get vendors
     * @param int $limit Optional vendors fetch limit
     * 
     * @return html
     */
    public function get_vendors($limit = false)
    {
        // Fetch vendors
        $vendors = $limit ? Vendor::limit($limit)->orderBy('created_at', 'DESC')->get() : Vendor::orderBy('created_at', 'DESC')->get();

        // Return view
        return view($limit ? 'admin.vendor.small_list' : 'admin.vendor.list', ['vendors' => $vendors]);
    }

    /**
     * Get agents
     * @param int $limit Optional agents fetch limit
     * 
     * @return html
     */
    public function get_agents($limit = false)
    {
        // Fetch agents
        $agents = $limit ? Shopper::limit($limit)->orderBy('created_at', 'DESC')->get() : Shopper::orderBy('created_at', 'DESC')->get();

        // Return view
        return view($limit ? 'admin.agent.small_list' : 'admin.agent.list', ['agents' => $agents]);
    }

    /**
     * Get users
     * @param int $limit Optional users fetch limit
     * 
     * @return html
     */
    public function get_users($limit = false)
    {
        // Fetch users
        $users = $limit ? User::limit($limit)->orderBy('created_at', 'DESC')->get() : User::orderBy('created_at', 'DESC')->get();

        // Return view
        return view($limit ? 'admin.user.small_list' : 'admin.user.list', ['users' => $users]);
    }

    /**
     * Get vendor view modals
     * @return html
     */
    public function vendor_view_modals()
    {
        // Fetch Vendors
        $vendors = Vendor::all();

        // Return view
        return view('admin.vendor.view', ['vendors' => $vendors]);
    }

    public function view_shoppers()
    {
        $vendors = Vendor::all();

        return view('admin.vendor.supermarket', ['vendors' => $vendors]);
    }

    /**
     * Get agent view modals
     * @return html
     */
    public function agent_view_modals()
    {
        // Fetch Agents
        $agents = Shopper::all();

        // Return view
        return view('admin.agent.view', ['agents' => $agents]);
    }

    /**
     * Assign shopper view modals
     * @return html
     */    
    public function assign_agent_view_modals()
    {
        // Fetch Agents
        $agents = Shopper::with('vendors')->get();

        //Fetch vendor
        $supermarkets = Vendor::all();


        // Return view
        return view('admin.agent.assign_shopper', ['agents' => $agents, 'supermarkets' => $supermarkets]);
    }

    /**
     * Get users view modals
     * @return html
     */
    public function user_view_modals()
    {
        // Fetch Users
        $users = User::all();

        // Return view
        return view('admin.user.view', ['users' => $users]);
    }
}
