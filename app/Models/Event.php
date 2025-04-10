<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'duration',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Convert times to Dhaka timezone when accessing
    public function getStartTimeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->timezone('Asia/Dhaka') : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->timezone('Asia/Dhaka') : null;
    }

    // App/Models/Event.php
    public function progressPercentage()
    {
        $start = $this->start_time->timestamp;
        $end = $this->end_time->timestamp;
        $now = now()->timestamp;

        if ($now <= $start) return 0;
        if ($now >= $end) return 100;

        $totalDuration = $end - $start;
        $elapsed = $now - $start;
        
        return round(($elapsed / $totalDuration) * 100);
    }
}