<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CompCentreController;
use App\Http\Controllers\Api\CompSyllabusController;
use App\Http\Controllers\Api\ComputerTraining;
use App\Http\Controllers\Api\DistrictBlockOfficeController;
use App\Http\Controllers\Api\FairProgrammeController;
use App\Http\Controllers\Api\MountaineeringController;
use App\Http\Controllers\Api\ServiceWebsiteController;
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

    Route::apiResource('comp-centres', CompCentreController::class)->except(['show']);
    Route::put('comp-centres/activate/{id}', [CompCentreController::class, 'activate']);

    Route::apiResource('vocatioanl-training-courses', VocationalTrainingController::class)->except(['show']);

    Route::controller(VocationalTrainingController::class)->prefix('vocational')->group(function () {
        Route::prefix('content')->group(function () {
            Route::post('store-content', 'storeContent');
            Route::put('activate-content/{id}', 'activateContent');
            Route::post('update-content/{id}', 'updateContent');
            Route::delete('destroy-content/{id}', 'destroyContent');
            Route::get('index-content', 'indexContent');
        });
        Route::prefix('centre-list')->group(function () {
            Route::post('store-centre', 'storeCentre');
            Route::put('activate-centre/{id}', 'activateCentre');
            Route::post('update-centre/{id}', 'updateCentre');
            Route::delete('destroy-centre/{id}', 'destroyCentre');
            Route::get('index-centre', 'indexCentre');
        });
    });

    Route::controller(MountaineeringController::class)->prefix('mountain')->group(function () {
        Route::prefix('general-body')->group(function () {
            Route::get('list', 'gbIndex');
            Route::post('store', 'gbStore');
            Route::put('update/{id}', 'gbUpdate');
            Route::delete('delete/{id}', 'gbDestroy');
        });
        Route::prefix('training-calendar')->group(function () {
            Route::get('list', 'tcIndex');
            Route::post('store', 'tcStore');
            Route::put('update/{id}', 'tcUpdate');
            Route::delete('delete/{id}', 'tcDestroy');
        });
    });

    Route::controller(FairProgrammeController::class)->prefix('fair-programme')->group(function () {
        Route::get('list', 'fpList');
        Route::post('store', 'fpStore');
        Route::get('edit/{uuid}', 'fpEdit');
        Route::post('update/{uuid}', 'fpUpdate');
        Route::delete('delete/{id}', 'fpDestroy');
        // ------------Gallery related starts ---------------
        Route::prefix('gallery')->group(function () {
            Route::post('store', 'fpGalleryStore');
            Route::post('update/{id}', 'fpGalleryUpdate');
            Route::delete('delete/{id}', 'fpGalleryDestroy');
            Route::delete('delete-image/{id}', 'fpGalleryImageDestroy');
            Route::put('show/{id}', 'fpShowInGallery');
        });
        // ------------Gallery related ends -----------------
    });

    Route::apiResource('district-block-offices', DistrictBlockOfficeController::class)->except(['show']);
    Route::put('district-block-offices/activate/{id}', [DistrictBlockOfficeController::class, 'activate']);
});
// Services app routes end -------------------------------

// Services website routes start -------------------------------
Route::controller(ServiceWebsiteController::class)->prefix('services')->group(function () {
    Route::get('districts', 'districts');
    Route::get('district-wise-block-offices', 'districtWiseBlockOffices');
    Route::get('computer-courses-all', 'computerCoursesAll');
    Route::get('photo-galleries', 'photoGalleryAll');
    Route::get('photo-galleries/{slug}', 'photoGallerySingle');
    Route::get('fairs-programms', 'fairProgrammesAll');
});
Route::get('banner/get', [BannerController::class, 'pageBanner']);
Route::get('com-training-courses/get', [ComputerTraining::class, 'courseList']);
Route::get('vocational/content/get', [VocationalTrainingController::class, 'contentdisplay']);
Route::get('vocational/centre-list/get', [VocationalTrainingController::class, 'centreListDisplay']);
// Services website routes end -------------------------------
