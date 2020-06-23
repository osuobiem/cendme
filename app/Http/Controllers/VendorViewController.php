<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorViewController extends Controller
{
    /**
     * Vendor dashboard index page
     */
    public function dashboard()
    {
        return view('vendor.dashboard');
    }
}
