<?php

namespace App\Http\Controllers\API;

use App\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Get all areas according to state
     * @param int $state_id State that area falls under
     * @return json
     */
    public function list($state_id)
    {
        $areas = Area::where('state_id', $state_id)->orderBy('name')->get();
        return response()->json($areas);
    }
}
