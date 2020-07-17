<?php

namespace App\Http\Controllers;

use App\Agent;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
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
        return view($limit ? 'admin.vendors.small_list' : 'admin.vendors.list', ['vendors' => $vendors]);
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
        return view($limit ? 'admin.agents.small_list' : 'admin.agents.list', ['agents' => $agents]);
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
        return view($limit ? 'admin.users.small_list' : 'admin.users.list', ['users' => $users]);
    }
}
