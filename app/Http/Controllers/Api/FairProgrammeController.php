<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FairProgramResource;
use App\Models\FairProgramme;
use App\Models\FairProgrammeGallery;
use App\Models\FairProgrammGalleryImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $slug = Str::slug($request->title);
        $check = FairProgramme::where('slug', $slug)->first();
        if ($check) {
            return response()->json(['errors' => ['title' => ['Programme with this title already exists.']]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->hasFile('cover') && $request->file('cover')->getSize() > 0) {
            $file = $request->file('cover');
            $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
            $directory = 'uploads/services/fairs-programmes';

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $filePath = $file->storeAs($directory, $filename, 'public');
        } else {
            $filePath = null;
        }

        $data = FairProgramme::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'occurance' => $request->occurance,
            'description' => $request->description ?? null,
            'uuid' => Str::uuid(),
            'added_by' => Auth::id(),
            'organisation' => 'services',
            'cover_image' => Storage::url($filePath),
        ]);

        return response()->json(['uuid' => $data->uuid], Response::HTTP_CREATED);
    }

    // ------------------------------------

    public function fpEdit($uuid)
    {
        $data = FairProgramme::where('uuid', $uuid)->first();

        if (!$data) {
            return response()->json(['errors' => ['uuid' => ['Programme not found.']]], Response::HTTP_NOT_FOUND);
        }

        return FairProgramResource::make($data);
    }

    // ------------------------------------

    public function fpUpdate(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'occurance' => ['required', Rule::in(['one-time', 'recurring'])],
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = FairProgramme::where('uuid', $uuid)->first();

        if (!$data) {
            return response()->json(['errors' => ['title' => ['Programme not found.']]], Response::HTTP_NOT_FOUND);
        }

        $slug = Str::slug($request->title);
        $check = FairProgramme::where('slug', $slug)->where('uuid', '!=', $uuid)->first();
        if ($check) {
            return response()->json(['errors' => ['title' => ['Programme with this title already exists.']]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->hasFile('cover') && $request->file('cover')->getSize() > 0) {
            if ($data) {
                $deletePath = str_replace('/storage', '', $data->cover_image);

                if (Storage::disk('public')->exists($deletePath)) {
                    Storage::disk('public')->delete($deletePath);
                }
            }

            $file = $request->file('cover');
            $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
            $directory = 'uploads/services/fairs-programmes';

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $filePath = $file->storeAs($directory, $filename, 'public');
        } else {
            $filePath = null;
        }

        $data->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'occurance' => $request->occurance,
            'description' => $request->description ?? null,
            'cover_image' => isset($filePath) ? Storage::url($filePath) : $data->cover_image,
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['uuid' => $data->uuid], Response::HTTP_OK);
    }

    // ------------------------------------

    public function fpDestroy($id) {}

    // ------------------------------------

    public function fpGalleryList() {}

    // ------------------------------------

    public function fpGalleryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'programmeDate' => 'required|before:today',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $data = FairProgramme::where('uuid', $request->uuid)->first();
            if (!$data) {
                return response()->json(['errors' => ['Programme not found.']], Response::HTTP_NOT_FOUND);
            }
            $fpid = $data->id;

            $insert = FairProgrammeGallery::create([
                'program_id' => $fpid,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'programme_date' => Date::createFromFormat('Y-m-d', $request->programmeDate),
                'description' => $request->description ?? null,
                'organisation' => 'services',
                'added_by' => Auth::id(),
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
                    $directory = 'uploads/services/fairs-programmes/gallery/' . $fpid;

                    if (!Storage::disk('public')->exists($directory)) {
                        Storage::disk('public')->makeDirectory($directory);
                    }

                    $filePath = $file->storeAs($directory, $filename, 'public');

                    FairProgrammGalleryImage::create([
                        'gallery_id' => $insert->id,
                        'image_path' => Storage::url($filePath),
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error in fpGalleryStore: ' . $th->getMessage());
            DB::rollBack();
            return response()->json(['errors' => ['Something went wrong. Please try again later.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ------------------------------------

    public function fpGalleryUpdate(Request $request, $id) {}

    // ------------------------------------

    public function fpGalleryDestroy($id) {}
}
