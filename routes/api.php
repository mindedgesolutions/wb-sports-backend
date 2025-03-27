<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CompSyllabusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComputerTraining;
use App\Http\Controllers\Api\VocationalTrainingController;

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
    Route::put('com-training-courses/activate/{id}', [ComputerTraining::class, 'activate']);

    Route::apiResource('comp-syllabus', CompSyllabusController::class)->except(['show', 'update']);
    Route::post('comp-syllabus/update/{id}', [CompSyllabusController::class, 'syllabusUpdate']);
    Route::put('comp-syllabus/activate/{id}', [CompSyllabusController::class, 'activate']);

    Route::apiResource('vocatioanl-training-courses', VocationalTrainingController::class)->except(['show']);

    Route::controller(VocationalTrainingController::class)->prefix('vocational')->group(function () {
        Route::prefix('content')->group(function () {
            Route::post('store-content', 'storeContent');
            Route::put('activate-content/{id}', 'activateContent');
            Route::post('update-content', 'updateContent');
            Route::delete('destroy-content', 'destroyContent');
            Route::get('index-content', 'indexContent');
        });
        Route::prefix('centre-list')->group(function () {
            Route::post('store-centre', 'storeCentre');
            Route::put('activate-centre', 'activateCentre');
            Route::post('update-centre', 'updateCentre');
            Route::delete('destroy-centre', 'destroyCentre');
            Route::get('index-centre', 'indexCentre');
        });
    });
});
// Services app routes end -------------------------------

// Services website routes start -------------------------------
Route::get('banner/get', [BannerController::class, 'pageBanner']);
Route::get('com-training-courses/get', [ComputerTraining::class, 'courseList']);
// Services website routes end -------------------------------
