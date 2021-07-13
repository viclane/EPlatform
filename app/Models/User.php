<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public function getAuthPassword()
    {
        return $this->mdp;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'login',
        'mdp',
        'type',
        'formation_id',
    ];

    protected $appends = [
        'is_admin',
        'is_instructor',
        'is_student',
        'full_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'mdp',
        'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function isValid()
    {
        return $this->type !== null;
    }

    public function getIsAdminAttribute()
    {
        return $this->type === 'admin';
    }

    public function getIsInstructorAttribute()
    {
        return $this->type === 'instructor';
    }

    public function getIsStudentAttribute()
    {
        return $this->type === 'student';
    }

    public function courses()
    {
        return $this->is_student
            ? $this->belongsToMany(Course::class, 'cours_users', 'user_id', 'cours_id')
            : $this->hasMany(Course::class, 'user_id');
    }

    /**
     * The courses in which the student is registered
     *
     * @return mixed
     */

    // public function liste() {
    //     return $this->belongsToMany(Cours::class, '', 'user_id', 'cours_id');
    //       ->withPivot('')
    //       ->withTimestamps();
    // }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
