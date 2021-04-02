<?php

namespace App\Http\Controllers\Api;

use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ulb;

class DropdownController extends Controller
{
    /**
     * Get the list of districts
     *
     * @return void
     */
    function getDistricts()
    {
        $distrcits = District::get();
        return response()->json(['data' => $distrcits, 'message' => 'List of districts'], 200);
    }

    /**
     * Get the ULB name by districts
     *
     * @param Request $request
     * @return void
     */
    function getUlbName(Request $request)
    {
        $distrcits = Ulb::when($request->filled('district'), function ($q) use ($request) {
            $q->where('district', $request->input('district'));
        })
            ->with('district_relation')
            ->get();
        return response()->json(['data' => $distrcits, 'message' => 'List of districts'], 200);
    }
}
