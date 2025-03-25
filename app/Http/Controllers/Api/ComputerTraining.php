<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\CompTrainCourseDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ComputerTraining extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_type' => 'required|string',
            'course_name' => 'required|string',
            'course_duration' => 'required|string',
            'course_eligibility' => 'required|string',
            'course_fees' => 'required|string',
            'organisation' => 'required|string',
        ], [
            '*.required' => ':Attribute is required',
        ], [
            'course_type' => 'Course type',
            'course_name' => 'Course name',
            'course_duration' => 'Course duration',
            'course_eligibility' => 'Course eligibility',
            'course_fees' => 'Course fees',
            'organisation' => 'Organisation',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            CompTrainCourseDetail::create([
                'course_type' => $request->input('course_type'),
                'course_name' => $request->input('course_name'),
                'course_duration' => $request->input('course_duration'),
                'course_eligibility' => $request->input('course_eligibility'),
                'course_fees' => $request->input('course_fees'),
                'organisation' => $request->input('organisation'),
            ]);

            DB::commit();

            return response()->json(['message' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
