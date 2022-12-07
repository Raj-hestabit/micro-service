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


Route::post('register', 'AuthHandler@register');
Route::post('login', 'AuthHandler@login');
Route::get('logout', 'AuthHandler@logout');

Route::post('user', 'UserHandler@store');
Route::get('user', 'UserHandler@show');

Route::post('notifications/mark-read', 'NotificationHandler@markRead');
Route::get('notifications-list', 'NotificationHandler@notifications');

Route::prefix('admin')->group(function () {
    Route::delete('user/{id}', 'AdminHandler@destory');
    Route::put('approve-request/{id}', 'AdminHandler@approveRequest');
    Route::get('pending-request-users', 'AdminHandler@pendingRequestUsers');
    Route::get('user/{id}', 'AdminHandler@show');

    Route::post('assign-teacher', 'AdminHandler@assignTeacher');
});
