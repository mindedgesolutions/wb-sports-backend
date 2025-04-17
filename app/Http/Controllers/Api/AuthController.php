<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'captchaEnter' => 'required',
        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'captchaEnter.required' => 'Captcha is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->captchaEnter !== $request->captchaText) {
            return response()->json(['errors' => ['Captcha is incorrect']], Response::HTTP_UNAUTHORIZED);
        }

        if (!Auth::attempt([
            'email' => $request->username,
            'password' => $request->password,
            'organisation' => $request->organisation,
        ])) {
            return response()->json(['errors' => ['Incorrect credentials']], Response::HTTP_UNAUTHORIZED);
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

    // --------------------------------------------

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6',
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'oldPassword.required' => 'Old password is required',
            'newPassword.required' => 'New password is required',
            'newPassword.min' => 'New password must be at least 6 characters',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = Auth::user();

        if (!password_verify($request->oldPassword, $user->password)) {
            return response()->json(['errors' => ['oldPassword' => ['Old password is incorrect']]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $newPassword = bcrypt($request->newPassword);
        User::where('id', $user->id)->update(['password' => $newPassword]);

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    // --------------------------------------------

    public function profileUpdate(ProfileUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            if ($request->hasFile('profileImg') && $request->file('profileImg')->getSize() > 0) {
                $file = $request->file('profileImg');
                $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
                $directory = 'uploads/profiles';

                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                if ($user) {
                    $deletePath = str_replace('/storage', '', $user->userDetails->profile_img);

                    if (Storage::disk('public')->exists($deletePath)) {
                        Storage::disk('public')->delete($deletePath);
                    }
                }

                $filePath = $file->storeAs($directory, $filename, 'public');
            }

            User::where('id', $user->id)->update([
                'name' => trim($request->name),
                'email' => $request->email,
            ]);

            UserDetail::where('user_id', $user->id)->update([
                'mobile' => $request->mobile,
                'profile_img' => $request->hasFile('profileImg') ? Storage::url($filePath) : $user->userDetails->profile_img ?? null,
            ]);

            DB::commit();
            return response()->json(['message' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
