<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompSyllabus;
use App\Models\CompTrainCourseDetail;
use App\Models\District;
use App\Models\FairProgrammeGallery;
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

    // --------------------------------

    public function photoGalleryAll()
    {
        $galleries = FairProgrammeGallery::where('show_in_gallery', true)
            ->with('cover')
            ->orderBy('programme_date', 'desc')
            ->get();

        return response()->json(['galleries' => $galleries], Response::HTTP_OK);
    }

    // --------------------------------

    public function photoGallerySingle($slug)
    {
        $gallery = FairProgrammeGallery::where('slug', $slug)->with(['images'])->first();

        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['gallery' => $gallery], Response::HTTP_OK);
    }

    // --------------------------------

    public function fairProgrammesAll() {}
}
