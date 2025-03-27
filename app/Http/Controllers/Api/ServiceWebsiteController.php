<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Response;

class ServiceWebsiteController extends Controller
{
    public function districts()
    {
        $districts = District::orderBy('name')->get();

        return response()->json(['data' => $districts], Response::HTTP_OK);
    }
}
