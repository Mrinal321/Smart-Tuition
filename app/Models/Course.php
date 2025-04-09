<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'course_name',
        'description',
        'demo_class0',
        'demo_class1',
        'demo_class2',
        'resources_link',
        'original_fee',
        'updated_fee',
        'discount'
    ];

    // Relationship to Teacher (assuming a Teacher model exists)
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
