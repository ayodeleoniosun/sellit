<?php

use Illuminate\Http\Request;
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

Route::group(
    [
        'prefix' => 'v1',
        'namespace' => 'App\Modules\Api\V1\Controllers'
    ],
    function () {
        //account routes
        Route::group(
            ['prefix' => 'account'],
            function () {
                Route::post('/signup', 'UserController@signUp')->name('user.signup');
                Route::post('/signin', 'UserController@signIn')->name('user.signin');
            }
        );

        //ads routes
        Route::group(
            ['prefix' => 'ads'],
            function () {
                Route::get('/', 'AdsController@index')->name('ads.index');
                Route::get('/{id}', 'AdsController@details')->name('ads.details')->where('id', '[0-9]+');
            }
        );

        //user routes
        Route::group(
            [
                'prefix' => 'user',
                'middleware' => ['v1.validate.user']
            ],
            function () {
                Route::get('/{id}', 'UserController@profile')->name('user.profile')
                    ->where('id', '[0-9]+');
                Route::put('/profile/update/personal-information', 'UserController@updatePersonalInformation')
                    ->name('profile.update.personal-information');
                Route::put('/profile/update/business-information', 'UserController@updateBusinessInformation')
                    ->name('profile.update.business-information');
                Route::post('/picture/upload', 'UserController@updateProfilePicture')
                    ->name('user.profile-picture.upload');
                Route::put('/password/change', 'UserController@changePassword')
                    ->name('user.password.change');

                Route::group(
                    ['prefix' => 'ads'],
                    function () {
                        Route::get('/', 'AdsController@myAds')->name('ads.mine');
                        Route::post('/', 'AdsController@post')->name('ads.post');
                        Route::post('/{id}/reviews', 'AdsController@postReviews')->name('ads.post-reviews');
                        Route::put('/{id}', 'AdsController@update')->name('ads.update')->where('id', '[0-9]+');
                        Route::post('/{id}/sort-options', 'AdsController@addSortOptions')->name('ads.add.sort-options');
                        Route::post('/{id}/upload-pictures', 'AdsController@uploadPictures')->name('ads.upload.pictures');
                        Route::delete('/{id}/picture/{pictureId}', 'AdsController@deletePicture')->name('ads.delete.picture');
                        Route::delete('/{id}/sort-option/{sortOptionId}', 'AdsController@deleteSortOption')->name('ads.delete.sort-options');
                    }
                );
            }
        );

        //categories routes
        Route::group(
            ['prefix' => 'category'],
            function () {
                Route::get('/', 'CategoryController@index')->name('category.index');
                Route::get('/{id}', 'CategoryController@categoryDetails')->name('category.details')->where('id', '[0-9]+');
                Route::get('/{id}/sub-categories', 'CategoryController@subCategories')
                    ->name('sub-categories.index')->where('id', '[0-9]+');
                Route::get('/{id}/sub-category/{subId}', 'CategoryController@subCategoryDetails')
                    ->name('sub-category.details')->where('id', '[0-9]+')
                    ->where('subId', '[0-9]+');
            }
        );

        //admin routes
        Route::group(
            ['prefix' => 'admin'],
            function () {
                Route::group(
                    ['prefix' => 'category'],
                    function () {
                        Route::post('/', 'CategoryController@addCategory')->name('category.add');
                        Route::post('/{id}', 'CategoryController@updateCategory')->name('category.update')->where('id', '[0-9]+');
                        Route::post('/sub-category', 'CategoryController@addSubCategory')
                            ->name('sub-category.add')->where('id', '[0-9]+');
                        Route::put('/sub-category/{subId}', 'CategoryController@updateSubCategory')
                            ->name('sub-category.update')->where('id', '[0-9]+')
                            ->where('subId', '[0-9]+');
                    }
                );
            }
        );
    }
);
