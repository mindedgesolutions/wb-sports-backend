<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompSyllabus;
use App\Models\CompTrainCourseDetail;
use App\Models\District;
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

    // --------------------------------

    public function computerCoursesAll()
    {
        $courses = CompTrainCourseDetail::where('is_active', true)->get();

        $syllabi = CompSyllabus::where('is_active', true)->get();

        return response()->json(['courses' => $courses, 'syllabi' => $syllabi], Response::HTTP_OK);
    }
}
