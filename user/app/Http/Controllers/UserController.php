<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\StudentParentDetails;
use App\Models\TeacherExperience;
use App\Models\TeacherSubjects;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use stdClass;

class UserController extends Controller
{
    public $notification_base_url;

    public function __construct(){
        $this->notification_base_url = 'http://notification.local/';
    }

    public function show(){
        $id = Auth::user()->id;
        $user = User::with('UserDetails', 'UserType', 'StudentParentDetails', 'StudentTeacher', 'TeacherExperience', 'TeacherStudent', 'TeacherSubjects')->find($id);
        if($user){
            return response()->json([
                'status'    => 'success',
                'message'   => 'User details',
                'user'      => new UserResource($user)
            ]);
        }else{
            return response()->json([
                'status'    => 'failure',
                'message'   => 'User not found',
                'user'      => new stdClass()
            ],404);
        }
    }

    public function store(Request $request){
        $userId = Auth::user()->id;
        if(empty($userId)) {
            return response()->json([
                'user'      => new stdClass(),
                'status'    => 'failure',
                'message'   => 'Please login first'
            ]);
        }

        if(Auth::user()->user_type == 1){
            return response()->json([
                'status'    => 'failure',
                'message'   => 'You are admin'
            ]);
        }

        if($request['image']){
            $file       = $request['image'];
            $filename   = time().'_'.$file->getClientOriginalName();
            if (!file_exists('users')) {
                mkdir('users', 0777, true);
            }
            $location   = 'users';
            $filePath = $file->storeAs($location, $filename, 'public');
            $fileUrl    = URL::to('/').Storage::url($filePath);
            $request->request->add(['profile_picture_url' => $fileUrl]);
        }
        $request->request->add(['status' => 0]);

        $userDetails = UserDetails::updateOrCreate(['user_id' => $userId],
                        $request->all());

        if(Auth::user()->user_type == 2){
            $teacherExperience  = TeacherExperience::updateOrCreate(['user_id' => $userId],
                                    $request->all());
            $teacherSubjects    = TeacherSubjects::updateOrCreate(['user_id' => $userId, 'subject_name' => $request->subject_name],
                                    $request->all());
        } elseif (Auth::user()->user_type == 3) {
            $studentParentDetails = StudentParentDetails::updateOrCreate(['user_id' => $userId],
                                    $request->all());
        }
        if($userDetails){
            $user = User::with('UserDetails', 'UserType', 'StudentParentDetails', 'StudentTeacher', 'TeacherExperience', 'TeacherStudent', 'TeacherSubjects')->find($userId);
            $user->name = $request->name;
            $user->save();
            return response()->json([
                'status'    => 'success',
                'message'   => 'Profile updation request sent to Admin',
                'user'      => new UserResource($user)
            ]);
        }else{
            return response()->json([
                'status'    => 'failure',
                'message'   => 'Something went wrong',
                'user'      => new stdClass()
            ]);
        }
    }

    public function userNotifications(){
        $id = Auth::user()->id;
        return Http::get($this->notification_base_url.'api/notifications-list/'.$id);
    }

    public function markRead(Request $request){
        $id = Auth::user()->id;
        return Http::post($this->notification_base_url.'api/notifications/mark-read/'.$id, $request->all());
    }
}
