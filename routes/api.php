<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComputerTraining;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

// Services app routes start -------------------------------
Route::middleware(['auth:api'])->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
    });

    Route::apiResource('banners', BannerController::class)->except(['show', 'update']);
    Route::post('banners/update/{id}', [BannerController::class, 'bannerUpdate']);
    Route::put('banners/activate/{id}', [BannerController::class, 'activate']);

    Route::apiResource('com-training-courses', ComputerTraining::class)->except(['show']);
});




// Services app routes end -------------------------------

// Services website routes start -------------------------------
Route::get('banner/get', [BannerController::class, 'pageBanner']);
// Services website routes end -------------------------------

