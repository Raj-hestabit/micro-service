<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentParentDetails extends Model
{
    use HasFactory;
    protected $table = 'student_parent_details';
    protected $fillable = ['user_id', 'p_name', 'relation'];
}
