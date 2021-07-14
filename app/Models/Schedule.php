<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date', 'end_date', 'course_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    protected $appends = [
        'format_start_date', 'format_end_date',
        'start_time', 'end_time'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getFormatStartDateAttribute()
    {
        return isset($this->start_date)
            ? $this->start_date->format('Y/m/d')
            : null;
    }

    public function getFormatEndDateAttribute()
    {
        return isset($this->end_date)
            ? $this->end_date->format('Y/m/d')
            : null;
    }

    public function getStartTimeAttribute()
    {
        return isset($this->start_date)
            ? $this->start_date->format('H:i')
            : null;
    }

    public function getEndTimeAttribute()
    {
        return isset($this->end_date)
            ? $this->end_date->format('H:i')
            : null;
    }
}
