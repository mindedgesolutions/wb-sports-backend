<?php

namespace App\Http\Controllers;

use App\Models\NewsEvent;
use Illuminate\Http\Request;

class NewsEventsController extends Controller
{
    public function index()
    {
        $data = NewsEvent::where('is_active', true)->paginate(10);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        //
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
