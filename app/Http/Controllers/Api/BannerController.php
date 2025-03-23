<?php

namespace App\Http\Controllers\Api;

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

class BannerController extends Controller
{
    public function index()
    {
        $data = Banner::where('organization', 'services')
            ->with('banner_added_by', 'banner_updated_by')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    // --------------------------------------------

    public function store(BannerRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
                $directory = 'uploads/services/banners';

                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                $filePath = $file->storeAs($directory, $filename, 'public');
            }

            Banner::create([
                'page_url' => $request->page,
                'page_title' => $request->pageTitle ?? null,
                'added_by' => Auth::id(),
                'image_path' => Storage::url($filePath),
                'organization' => 'services',
            ]);

            DB::commit();

            return response()->json(['message' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // --------------------------------------------

    public function show(string $id)
    {
        //
    }

    // --------------------------------------------

    public function update(Request $request, string $id)
    {
        //
    }

    // --------------------------------------------

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $data = Banner::findOrFail($id);
            $filePath = str_replace('/storage', '', $data->image_path);

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Banner::where('id', $id)->delete();

            DB::commit();

            return response()->json(['message' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // --------------------------------------------

    public function activate(Request $request, string $id)
    {
        Banner::where('id', $id)->update(['is_active' => $request->is_active]);

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }
}
