<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type',
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function UserType(){
        return $this->belongsTo(UserType::class, 'user_type');
    }

    public function UserDetails(){
        return $this->hasOne(UserDetails::class);
    }

    public function StudentParentDetails(){
        return $this->hasMany(StudentParentDetails::class);
    }

    public function TeacherExperience(){
        return $this->hasOne(TeacherExperience::class);
    }

    public function StudentTeacher(){
        return $this->hasMany(StudentTeacherRelation::class, 'student_id');
    }

    public function TeacherStudent(){
        return $this->hasMany(StudentTeacherRelation::class, 'teacher_id');
    }

    public function TeacherSubjects(){
        return $this->hasMany(TeacherSubjects::class);
    }
}
