<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsEventsRequest;
use App\Models\NewsEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsEventsController extends Controller
{
    public function index()
    {
        $data = NewsEvent::orderBy('id', 'desc')->paginate(10);
        return response()->json($data);
    }

    public function store(NewsEventsRequest $request)
    {
        try {
            DB::beginTransaction();

            $eventSlug = Str::slug($request->title);
            $check = NewsEvent::where('slug', $eventSlug)->first();
            $filePath = '';

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
                $directory = 'uploads/services/news-events';

                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                if ($check) {
                    $deletePath = str_replace('/storage', '', $check->file_path);

                    if (Storage::disk('public')->exists($deletePath)) {
                        Storage::disk('public')->delete($deletePath);
                    }
                }

                $filePath = $file->storeAs($directory, $filename, 'public');
            }

            if ($check) {
                NewsEvent::where('slug', $eventSlug)->update([
                    'title' => trim($request->title),
                    'description' => $request->description ? trim($request->description) : null,
                    'file_path' => Storage::url($filePath),
                    'event_date' => $request->eventDate ? Date::createFromFormat('Y-m-d', $request->eventDate) : null,
                ]);
            } else {
                NewsEvent::create([
                    'title' => trim($request->title),
                    'slug' => $eventSlug,
                    'description' => $request->description ? trim($request->description) : null,
                    'file_path' => Storage::url($filePath),
                    'event_date' => $request->eventDate ? Date::createFromFormat('Y-m-d', $request->eventDate) : null,
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

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
