<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTeacherRelation extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'teacher_id'];

    protected $table = 'student_teacher_relations';

}
