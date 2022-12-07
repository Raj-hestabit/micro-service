<?php

use App\Mail\SendEmailTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
// Route::get('account-approve-mail', function(){
//     $email = new SendEmailTest();
//     Mail::to('test@gmail.com')->send($email);
// });

Route::post('account-approve-mail', 'NotificationController@accountApprove');
Route::post('teacher-assign', 'NotificationController@accountApprove');

    Route::post('notifications/mark-read/{id}', 'NotificationController@markRead');
    Route::get('notifications-list/{id}', 'NotificationController@notifications');
