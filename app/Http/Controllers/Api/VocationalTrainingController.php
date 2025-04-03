<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VocationalTraining;
use App\Models\VocationalTrainingCentre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class VocationalTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexContent() {
        $content = VocationalTraining::paginate(10);

        return response()->json(['content' => $content], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ], [
            '*.required' => ':Attribute is required',
        ], [
            'content' => 'content',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            VocationalTraining::create([
                'content' => $request->content,
            ]);

            DB::commit();

            return response()->json(['message' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function activateContent(Request $request, string $id)
       {
            VocationalTraining::where('id', $id)->update(['is_active' => $request->is_active]);

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }



    //


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function updateContent(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ], [
            '*.required' => ':Attribute is required',
        ], [
            'content' => 'content',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            VocationalTraining::where('id', $id)->update([
                'content' => $request->content,
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
     * Remove the specified resource from storage.
     */
    public function destroyContent(string $id)
    {
        VocationalTraining::where('id', $id)->delete();
        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }


    public function contentdisplay() // <-- Add Request here
    {
        try {

            $content = VocationalTraining::where('is_active', true)->get();

            return response()->json(['content' => $content], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }











    //    Centre Start

    public function indexCentre(string $id)
    {
        //
    }

    public function storeCentre(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'districtId' => 'required|string',
            'nameOfCentre' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
        ], [
            '*.required' => ':Attribute is required',
        ], [

            'districtId' => 'district_id',
            'nameOfCentre' =>  'name_of_centre',
            'address'  => 'address',
            'phone' => 'phone',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            VocationalTrainingCentre::create([
                'district_id' => $request->input('districtId'),
                'name_of_centre' => $request->input('nameOfCentre'),
                'address'  => $request->input('address'),
                'phone' => $request->input('phone'),
            ]);

            DB::commit();

            return response()->json(['message' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function activateCentre(Request $request,string $id)
    {
        VocationalTrainingCentre::where('id', $id)->update(['is_active' => $request->is_active]);

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    public function updateCentre(string $id)
    {
        //
    }
    public function destroyCentre(string $id)
    {
        //
    }
}
