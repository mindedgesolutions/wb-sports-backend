<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MountainGeneralBody;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MountaineeringController extends Controller
{
    // General body methods start -------------------------------

    public function gbIndex() {}

    // --------------------------------

    public function gbStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designation' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'desc' => 'required|string|max:255'
        ], [
            '*.required' => ':Attribute is required',
            '*.max' => ':Attribute must not exceed :max characters'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        MountainGeneralBody::create([
            'designation' => $request->designation ?? null,
            'name' => $request->name,
            'description' => $request->desc,
            'organisation' => 'services',
            'added_by' => Auth::id()
        ]);

        return response()->json(['message' => 'General body created successfully'], Response::HTTP_CREATED);
    }

    // --------------------------------

    public function gbUpdate(Request $request, $id) {}

    // --------------------------------

    public function gbDestroy($id) {}

    // General body methods end -------------------------------
}
