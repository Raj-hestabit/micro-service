<?php

namespace App\Http\Controllers;

use App\Events\AssignTeacherToStudentEvent;
use App\Events\RequestApprove;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function accountApprove(Request $request){
        $user = User::find($request->id);
        event(new RequestApprove($user));
    }

    public function assignTeacher(Request $request){
        $user = User::find($request->id);
        event(new AssignTeacherToStudentEvent($user));
    }

    public function notifications($id){
        $user = User::find($id);
        $notifications = $user->unreadNotifications;
        if($notifications){
            return response()->json([
                'status'        => 'success',
                'message'       => 'Requests notifications',
                'notifications' => $notifications
            ]);
        } else {
            return response()->json([
                'status'        => 'failure',
                'message'       => 'No request found',
                'notifications' => []
            ]);
        }
    }

    public function markRead(Request $request, $id){
        $user = User::find($id);
        $user->unreadNotifications
        ->when($request->input('id'), function ($query) use ($request) {
            return $query->where('id', $request->input('id'));
        })
        ->markAsRead();

        return response()->json([
            'status'        => 'success',
            'message'       => 'Notifications read',
        ]);
    }
}
