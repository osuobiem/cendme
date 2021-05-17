<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Get all states
     * @return json
     */
    public function list()
    {
        $states = State::orderBy('name')->get();
        return response()->json($states);
    }
}
