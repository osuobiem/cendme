<?php

namespace App\Http\Controllers;

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
        // Extract admin ID
        // $vendor_id = Auth::guard('admins')->user()->id;


        return view('admin.dashboard');
    }
}
