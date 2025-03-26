<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        if (!Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            return response()->json(['message' => ['Incorrect credentials']], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('AuthToken')->accessToken;

        return UserResource::make($user)->additional(['token' => $token]);
    }

    // --------------------------------------------

    public function forgotPassword(Request $request) {}

    // --------------------------------------------

    public function resetPassword(Request $request) {}

    // --------------------------------------------

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // --------------------------------------------

    public function me()
    {
        return UserResource::make(Auth::user());
    }
}
