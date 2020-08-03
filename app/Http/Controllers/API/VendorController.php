<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Get all vendors according to area
     * @param int $area_id Area that vendor falls under
     * @return json
     */
    public function list($area_id)
    {
        // Get vendors
        $vendors = Vendor::where('area_id', $area_id)
            ->orderBy('orders_count', 'desc')
            ->orderBy('business_name', 'asc')->get();

        return response()->json([
            'vendors' => $vendors,
            'photo_url' => url('/') . Storage::url('vendors/')
        ]);
    }
}
