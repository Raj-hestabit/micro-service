<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'user_type' => new UserTypeResource($this->UserType),
            'user_details' => new UserDetailResource($this->UserDetails),
            $this->mergeWhen($this->user_type == 3, [
                'parent_details'    => StudentParentDetailResource::collection($this->StudentParentDetails),
                'student_teacher'   => StudentTeacherResource::collection($this->StudentTeacher)
            ]),
            $this->mergeWhen($this->user_type == 2, [
                'total_experience'  => new TeacherExperienceResource($this->TeacherExperience),
                'teacher_subjects'  => TeacherSubjectsResource::collection($this->TeacherSubjects)
                // 'teacher_student'   => StudentTeacherResource::collection($this->TeacherStudent)
            ]),
        ];
    }
}
