<?php

namespace App\Models;

use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule',
        'user_id',
        'formation_id'
    ];

    protected $with = ['schedules'];

    protected $appends = [
        'stat_datas'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'user_course', 'course_id', 'user_id');
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function schedules()
    {
        return $this->hasMany(Planning::class, 'course_id');
    }

    public function getStatDatasAttribute()
    {
        $schedules = $this->schedules;
        $data = [
            'passed' => 0,
            'coming' => 0,
        ];

        if (count($schedules)) {
            $date = Carbon::now()->format('Y-m-d H:i');
            $data['passed'] = CourseRepository::schedulesFilter($schedules, null, $date, true)->count();
            $data['coming'] = CourseRepository::schedulesFilter($schedules, $date, null, true)->count();
        }

        return $data;
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Course $course) {
            $course->schedules()->delete();
        });
    }
}
