<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompCentreRequest;
use App\Models\CompCenter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompCentreController extends Controller
{
    public function index()
    {
        $data = CompCenter::with('district')->paginate(10);

        return response()->json($data, Response::HTTP_OK);
    }

    // --------------------------------------------

    public function store(CompCentreRequest $request)
    {
        CompCenter::create([
            'district_id' => (int)$request->district,
            'yctc_name' => $request->yctcName,
            'yctc_code' => $request->yctcCode ?? null,
            'center_category' => $request->centreCategory ?? null,
            'address_line_1' => $request->address1 ?? null,
            'address_line_2' => $request->address2 ?? null,
            'address_line_3' => $request->address3 ?? null,
            'city' => $request->city ?? null,
            'pincode' => $request->pincode ?? null,
            'center_incharge_name' => $request->inchargeName ?? null,
            'center_incharge_mobile' => $request->inchargeMobile ?? null,
            'center_incharge_email' => $request->inchargeEmail ?? null,
            'center_owner_name' => $request->ownerName ?? null,
            'center_owner_mobile' => $request->ownerMobile ?? null,
            'added_by' => Auth::id(),
            'slug' => Str::slug($request->yctcName),
        ]);

        return response()->json(['message' => 'Center added successfully'], Response::HTTP_CREATED);
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
        //
    }
}
