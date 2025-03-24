<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        try {
            DB::beginTransaction();

            $check = Banner::where('page_url', $request->page)->first();
            $filePath = '';

            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
                $directory = 'uploads/services/banners';

                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                if ($check) {
                    $deletePath = str_replace('/storage', '', $check->image_path);

                    if (Storage::disk('public')->exists($deletePath)) {
                        Storage::disk('public')->delete($deletePath);
                    }
                }

                $filePath = $file->storeAs($directory, $filename, 'public');
            }

            if ($check) {
                Banner::where('page_url', $request->page)->update([
                    'page_title' => $request->pageTitle ?? null,
                    'updated_by' => Auth::id(),
                    'image_path' => Storage::url($filePath),
                ]);
            } else {
                Banner::create([
                    'page_url' => $request->page,
                    'page_title' => $request->pageTitle ?? null,
                    'added_by' => Auth::id(),
                    'image_path' => Storage::url($filePath),
                    'organization' => 'services',
                ]);
            }

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
