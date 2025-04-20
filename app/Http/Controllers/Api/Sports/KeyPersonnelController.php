<?php

namespace App\Http\Controllers\Api\Sports;

use App\Http\Controllers\Controller;
use App\Models\SpKeyPersonnel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KeyPersonnelController extends Controller
{
    public function index()
    {
        $data = SpKeyPersonnel::orderBy('show_order', 'asc')
            ->orderBy('id')
            ->paginate(10);

        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    // ---------------------------

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) {
                    $slug = Str::slug($value);
                    if (SpKeyPersonnel::where('slug', $slug)->exists()) {
                        $fail('Name exists');
                    }
                }
            ],
            'designation' => 'required|max:255',
            'department' => 'required|max:255',
            'govt' => 'required|max:255',
            'profileImg' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100',
        ], [
            '*.required' => ':Attribute is required',
            '*.max' => ':Attribute must not exceed :max characters',
            'profileImg.image' => 'Profile image must be a valid image file',
            'profileImg.mimes' => 'Profile image must be of type jpeg, png, jpg, or gif',
            'profileImg.max' => 'Profile image size must not exceed 100KB',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->hasFile('profileImg') && $request->file('profileImg')->getSize() > 0) {
            $file = $request->file('profileImg');
            $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
            $directory = 'uploads/sports/key-personnel';

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            $filePath = $file->storeAs($directory, $filename, 'public');
        }

        SpKeyPersonnel::create([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
            'rank' => $request->rank ? trim($request->rank) : null,
            'designation' => trim($request->designation),
            'department' => trim($request->department),
            'govt' => trim($request->govt),
            'image_path' => $request->hasFile('profileImg') ? Storage::url($filePath) : null,
            'added_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'success'], Response::HTTP_CREATED);
    }

    // ---------------------------

    public function updateMember(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($id) {
                    $slug = Str::slug($value);
                    if (SpKeyPersonnel::where('slug', $slug)
                        ->where('id', '!=', $id)
                        ->exists()
                    ) {
                        $fail('Name exists');
                    }
                }
            ],
            'designation' => 'required|max:255',
            'department' => 'required|max:255',
            'govt' => 'required|max:255',
            'profileImg' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100',
        ], [
            '*.required' => ':Attribute is required',
            '*.max' => ':Attribute must not exceed :max characters',
            'profileImg.image' => 'Profile image must be a valid image file',
            'profileImg.mimes' => 'Profile image must be of type jpeg, png, jpg, or gif',
            'profileImg.max' => 'Profile image size must not exceed 100KB',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = SpKeyPersonnel::findOrFail($id);

        if ($request->hasFile('profileImg') && $request->file('profileImg')->getSize() > 0) {
            $file = $request->file('profileImg');
            $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
            $directory = 'uploads/sports/key-personnel';

            if ($data) {
                $deletePath = str_replace('/storage', '', $data->image_path);

                if (Storage::disk('public')->exists($deletePath)) {
                    Storage::disk('public')->delete($deletePath);
                }
            }

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            $filePath = $file->storeAs($directory, $filename, 'public');
        }

        SpKeyPersonnel::whereId($id)->update([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
            'rank' => $request->rank ? trim($request->rank) : null,
            'designation' => trim($request->designation),
            'department' => trim($request->department),
            'govt' => trim($request->govt),
            'image_path' => $request->hasFile('profileImg') ? Storage::url($filePath) : $data->image_path ?? null,
            'added_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    // ---------------------------

    public function destroy(string $id)
    {
        SpKeyPersonnel::where('id', $id)->delete();

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    // ---------------------------

    public function activate(Request $request, string $id)
    {
        SpKeyPersonnel::where('id', $id)->update(['is_active' => $request->is_active]);

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    // ---------------------------

    public function keyPersonnelAll()
    {
        $data = SpKeyPersonnel::where('is_active', true)
            ->orderBy('show_order', 'asc')
            ->orderBy('id')
            ->get();

        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    // ---------------------------

    public function keyPersonnelSetOrder(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            SpKeyPersonnel::where('id', $value['id'])->update(['show_order' => $key]);
        }

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }
}
