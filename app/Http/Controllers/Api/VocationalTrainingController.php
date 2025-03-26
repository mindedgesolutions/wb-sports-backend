<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VocationalTraining;
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
    public function index()
    {
        //
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
                   'content' => $request->input('content'),
               ]);

               DB::commit();

               return response()->json(['message' => 'success'], Response::HTTP_CREATED);
           } catch (\Throwable $th) {
               Log::error($th->getMessage());
               DB::rollBack();
               return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
           }
       }


    public function activate(Request $request, string $id)
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
