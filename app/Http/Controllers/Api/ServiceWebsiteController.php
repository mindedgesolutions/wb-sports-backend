<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\DistrictBlockOffice;
use Illuminate\Http\Response;

class ServiceWebsiteController extends Controller
{
    public function districts()
    {
        $districts = District::orderBy('name')->get();

        return response()->json(['data' => $districts], Response::HTTP_OK);
    }

    // --------------------------------

    public function districtWiseBlockOffices()
    {
        $data = District::with('districtOffices')->orderBy('name')->get();

        return response()->json(['data' => $data], Response::HTTP_OK);
    }
}
