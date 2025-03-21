<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $table = 'teachers';
    protected $fillable = [
        'name',
        'university_name',
        'department_name',
        'profile_pic',
        'total_star',
        'star_count',
        'university_id_image',
        'email',
        'phone_number',
        'social_media_link',
        'user_teacher_id',
    ];
}
