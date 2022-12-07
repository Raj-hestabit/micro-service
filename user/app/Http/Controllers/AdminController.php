<?php

namespace App\Http\Controllers;

use App\Events\AssignTeacherToStudentEvent;
use App\Events\RequestApprove;
use App\Http\Requests\AssignTeacherRequest;
use App\Http\Resources\UserResource;
use App\Jobs\AccountApprove;
use App\Jobs\AssignTeacher;
use App\Models\StudentTeacherRelation;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class AdminController extends Controller
{
    public function destroy($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status'    => 'failure',
                'message'   => 'User not found'
            ]);
        }

        DB::beginTransaction();
        try {
            $user = User::find($id)->delete();
            $userDetails = UserDetails::where('user_id',$id)->delete();
            DB::commit();
            if($userDetails && $user){
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'User deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'    => 'failure',
                    'message'   => 'something went wrong'
                ]);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                'status'    => 'failure',
                'message'   => 'something went wrong',
                'error'     => $ex->getMessage()
            ]);
        }


    }

    public function approveRequest(Request $request, $id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status'    => 'failure',
                'message'   => 'User not found'
            ]);
        }
        $userDetails = UserDetails::where('user_id', $id)->where('status', 0)->first();
        if(!$userDetails){
            return response()->json([
                'status'    => 'failure',
                'message'   => 'Request not found for this user'
            ]);
        }

        $approve = UserDetails::updateOrCreate(['user_id' => $id],
                        $request->all());

        if($approve){
            dispatch(new AccountApprove($user));
            return response()->json([
                'status'    => 'success',
                'message'   => 'Request approve successfully'
            ]);
        } else {
            return response()->json([
                'status'    => 'failure',
                'message'   => 'something went wrong'
            ]);
        }
    }

    public function pendingRequestUsers(){
        $users = User::with(['UserType', 'UserDetails'])->whereHas('UserDetails',function($q){
            $q->where('status', 0);
        })->where('user_type','!=',1)->get();

        if($users){
            return response()->json([
                'status'    => 'success',
                'message'   => 'Pending request users list',
                'users'     => UserResource::collection($users)
            ]);
        } else {
            return response()->json([
                'status'    => 'failure',
                'users'     => [],
                'message'   => 'No request found'
            ]);
        }
    }

    public function show($id){
        if(Auth::user()->user_type != 1){
            if($id != Auth::user()->id){
                return response()->json([
                    'status'    => 'failure',
                    'message'   => 'You are not authorized person to view this details',
                ],400);
            }
        }

        $user = User::with('UserDetails', 'UserType', 'StudentParentDetails', 'TeacherExperience', 'StudentTeacher')->find($id);
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

    public function assignTeacher(AssignTeacherRequest $request){
        $studentDetails = User::find($request->student_id);
        if(empty($studentDetails) || $studentDetails->user_type != 3){
            return response()->json([
                'status'    => 'failure',
                'message'   => 'Student not found with this student id',
            ],404);
        }

        $teacherDetails = User::find($request->teacher_id);
        if(empty($teacherDetails) || $teacherDetails->user_type != 2){
            return response()->json([
                'status'    => 'failure',
                'message'   => 'Teacher not found with this teacher id',
            ],404);
        }

        $assignTeacher = StudentTeacherRelation::updateOrCreate(
                        [
                            'student_id' => $request->student_id,
                            'teacher_id' => $request->teacher_id,
                        ], $request->all());

        if($assignTeacher){
            dispatch(new AssignTeacher($teacherDetails));
            // event(new AssignTeacherToStudentEvent($teacherDetails));
            return response()->json([
                'status'    => 'success',
                'message'   => 'Teacher assign to student',
            ]);
        }else{
            return response()->json([
                'status'    => 'failure',
                'message'   => 'Something went wrong',
            ],400);
        }
    }
}
