<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date', 'date_fin', 'course_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    protected $appends = [
        'start_date', 'end_date',
        'start_time', 'end_time'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getDebutDateAttribute()
    {
        return $this->start_date
            ? $this->start_date->format('Y-m-d')
            : null;
    }

    public function getFinDateAttribute()
    {
        return $this->end_date
            ? $this->end_date->format('Y-m-d')
            : null;
    }

    public function getDebutHeureAttribute()
    {
        return $this->start_date
            ? $this->start_date->format('H:i')
            : null;
    }

    public function getFinHeureAttribute()
    {
        return $this->end_date
            ? $this->end_date->format('H:i')
            : null;
    }
}
