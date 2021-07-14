<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    protected $with = [
        'users'
    ];

    protected $appends = [
        'students'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getStudentsAttribute()
    {
        return $this->users()->where('type', 'student')->get();
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Formation $formation) {
            foreach ($formation->courses as $course) {
                $course->delete();
            }

            $formation->users()->where('type', 'student')->delete();
        });
    }
}
