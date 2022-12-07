<?php

use App\Models\User;
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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::get('logout', 'AuthController@logout');

    Route::post('user', 'UserController@store');
    Route::get('user', 'UserController@show');

    Route::post('notifications/mark-read', 'UserController@markRead');
    Route::get('notifications-list', 'UserController@userNotifications');

    Route::prefix('admin')->middleware('isAdmin')->group(function () {
        Route::delete('user/{id}', 'AdminController@destroy');
        Route::post('assign-teacher', 'AdminController@assignTeacher');
        Route::put('approve-request/{id}', 'AdminController@approveRequest');
        Route::get('pending-request-users', 'AdminController@pendingRequestUsers');
        Route::get('user/{id}', 'AdminController@show');
    });
});

// Route::get('email-test', function(){
//     // $details['email'] = 'test@gmail.com';
//     $user = User::find(2);
//     dispatch(new App\Jobs\AccountApprove($user));
//     dd('done');
// });

