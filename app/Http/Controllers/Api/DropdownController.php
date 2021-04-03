<?php

namespace App\Http\Controllers\Api;

use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Distance;
use App\Models\Extent;
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
        return response()->json(['data' => $distrcits, 'message' => 'List of ULB Names'], 200);
    }

    /**
     * Get the list of extents
     *
     * @return void
     */
    function getExtents()
    {
        $extents = Extent::get();
        return response()->json(['data' => $extents, 'message' => 'List of extents'], 200);
    }

    /**
     * Get the list of districts
     *
     * @return void
     */
    function getDistances()
    {
        $distances = Distance::get();
        return response()->json(['data' => $distances, 'message' => 'List of distances'], 200);
    }
}
