<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/register', 'register')->name('auth.register');
            Route::post('/login', 'login')->name('auth.login');
            Route::post('/forgot-password', 'forgotPassword')->name('auth.forgot_password');
            Route::post('/reset-password', 'resetPassword')->name('auth.reset_password');
        });
    });

    Route::middleware(['auth:sanctum', 'is_verified_user'])->prefix('users')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/{slug}', 'profile')->name('user.profile');

            Route::prefix('profile')->group(function () {
                Route::post('/update/picture', 'updateProfilePicture')->name('user.update.profile.picture');
                Route::put('/update/personal-information', 'updateProfile')->name('user.update.profile');
                Route::put('/update/business-information', 'updateBusinessProfile')->name('user.update.profile.business');
                Route::put('/update/password', 'updatePassword')->name('user.update.password');
                Route::get('/logout', 'logout')->name('user.logout');
            });
        });

        Route::controller(AdsController::class)->prefix('/ads')->group(function () {
            Route::get('/', 'myAds')->name('ads.mine');
            Route::post('/', 'store')->name('ads.store');
            Route::post('/{id}/reviews', 'storeReviews')->name('ads.reviews.store');
            Route::put('/{id}', 'update')->name('ads.update');
            Route::post('/{id}/sort-options', 'storeSortOptions')->name('ads.sort_options.store');
            Route::post('/upload/pictures', 'uploadPictures')->name('ads.pictures.upload');
            Route::delete('/{id}/picture/{pictureId}', 'deletePicture')->name('ads.picture.delete');
            Route::delete('/{id}', 'delete')->name('ads.delete');
            Route::delete('/{id}/sort-option/{sortOptionId}', 'deleteSortOption')->name('ads.sort_options.delete');
        });
    });

    Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
        Route::get('/overview', [AdminController::class, 'overview'])->name('admin.overview');
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');

        Route::controller(CategoryController::class)->prefix('categories')->group(function () {
            Route::get('/', 'index')->name('category.index');
            Route::post('/', 'store')->name('category.store');
            Route::post('/{slug}', 'update')->name('category.update');
        });

        Route::controller(CategoryController::class)->prefix('sub-categories')->group(function () {
            Route::get('/', 'subCategories')->name('sub_category.index');
            Route::post('/', 'addSubCategory')->name('sub_category.store');
            Route::put('/{slug}', 'updateSubCategory')->name('sub_category.update');
            Route::post('/sort-options/{subId}', 'storeSortOptions')->name('sub_category.sort_options.store');
        });
    });

    Route::prefix('ads')->group(function () {
        Route::controller(AdsController::class)->group(function () {
            Route::get('/', 'index')->name('ads.index');
            Route::get('/{slug}', 'view')->name('ads.view');
        });
    });

    Route::prefix('category')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->name('category.index');
            Route::get('/{id}', 'view')->name('category.view');
            Route::get('/{id}/sub-categories', 'subCategories')->name('sub_categories.index');
            Route::get('/{id}/sub-category/{subId}', 'viewSubCategory')->name('sub_category.view');
        });
    });
});
