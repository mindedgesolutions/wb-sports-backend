<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FairProgramResource;
use App\Models\FairProgramme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class FairProgrammeController extends Controller
{
    public function fpList()
    {
        $data = FairProgramme::where('organisation', 'services')->orderBy('id', 'desc')->paginate(10);

        return FairProgramResource::collection($data);
    }

    // ------------------------------------

    public function fpStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'occurance' => ['required', Rule::in(['one-time', 'recurring'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $slug = Str::slug($request->title);
        $check = FairProgramme::where('slug', $slug)->first();
        if ($check) {
            return response()->json(['errors' => ['title' => ['Programme with this title already exists.']]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = FairProgramme::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'occurance' => $request->occurance,
            'description' => $request->description ?? null,
            'uuid' => Str::uuid(),
            'added_by' => Auth::id(),
            'organisation' => 'services',
        ]);

        return response()->json(['uuid' => $data->uuid], Response::HTTP_CREATED);
    }

    // ------------------------------------

    public function fpUpdate(Request $request, $id) {}

    // ------------------------------------

    public function fpActivate($id) {}

    // ------------------------------------

    public function fpDestroy($id) {}
}
