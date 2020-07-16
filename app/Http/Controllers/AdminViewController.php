<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminViewController extends Controller
{
    /**
     * Admin Login Page
     */
    public function login()
    {
        return view('admin.login');
    }
}
