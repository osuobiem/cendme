<?php

namespace App\Http\Controllers;

use App\Agent;
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
        $agents = $limit ? Agent::limit($limit)->orderBy('created_at', 'DESC')->get() : Agent::orderBy('created_at', 'DESC')->get();

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

    /**
     * Get agent view modals
     * @return html
     */
    public function agent_view_modals()
    {
        // Fetch Agents
        $agents = Agent::all();

        // Return view
        return view('admin.agent.view', ['agents' => $agents]);
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
