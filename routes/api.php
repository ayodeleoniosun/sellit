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
        Route::group(
            ['prefix' => 'account'],
            function () {
                Route::post('/signup', 'UserController@signUp')->name('user.signup');
                Route::post('/signin', 'UserController@signIn')->name('user.signin');
            }
        );

        Route::group(
            [
                'prefix' => 'user',
                'middleware' => ['v1.validate.user']
            ],
            function () {
                Route::get('/{id}', 'UserController@profile')->name('user.profile')->where('id', '[0-9]+');
                Route::put('/profile/update/personal-information', 'UserController@updatePersonalInformation')
                ->name('profile.update.personal-information');
                Route::put('/profile/update/business-information', 'UserController@updateBusinessInformation')
                ->name('profile.update.business-information');
                Route::post('/picture/upload', 'UserController@updateProfilePicture')->name('user.profile-picture.upload');
                Route::put('/password/change', 'UserController@changePassword')->name('user.password.change');
            }
        );
    }
);
