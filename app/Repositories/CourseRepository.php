<?php

namespace App\Repositories;


use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class CourseRepository
{
    public function newQuery()
    {
        return Course::query();
    }

    public function getCourses($user_id = null)
    {
        $courses = $this->newQuery();
        if ($user_id) {
            $courses = $courses->where('user_id', $user_id);
        }

        return $courses;
    }

    public function getFormationCourses($formation_id = null)
    {
        $courses = $this->newQuery();

        if ($formation_id) {
            $courses = $courses->where('formation_id', $formation_id);
        }

        return $courses;
    }

    public function getOthersFormationCourses($formation_id = null)
    {
        $courses = $this->newQuery();

        if ($formation_id) {
            $courses = $courses->where('formation_id', $formation_id);
        }

        if ($user = request()->user()) {
            $courses = $courses->leftJoin('cours_users', 'cours.id', '=', 'cours_users.course_id')
                ->where('cours_users.user_id', '=', null);
        }

        return $courses;
    }

    /**
     * Juste accessible a l'administrateur
     */
    public function getCoursesSchedules(
        $course = null,
        $dateStart = null,
        $dateFin = null,
        $orderColumn = 'start_date',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        if (!request()->user()->is_admin) {
            abort(500);
        }

        if ($course) {
            if (!($course instanceof Course)) {
                throw new \Exception('Invalid course');
            }

            $schedules = $course->schedules->all();
        } else {
            $schedules = Schedule::all();
        }

        return self::schedulesFilter($schedules, $dateStart, $dateFin, false, $orderColumn, $order, $dateFormat);
    }

    public function getUserCoursesSchedules(
        $course = null,
        $dateStart = null,
        $dateFin = null,
        $user = null,
        $orderColumn = 'start_date',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        $user = $user ?? request()->user();

        if (!$user || $user->is_admin) {
            abort(500);
        }

        if ($user->is_instructor || $user->is_student) {
            $schedules = [];

            if ($course) {
                if (!($course instanceof Course)) {
                    throw new \Exception('Invalid course');
                }

                $schedules = $course->schedules->all();
            } else {
                foreach ($user->courses->pluck('schedules')->all() as $course_schedules) {
                    $schedules = array_merge($schedules, $course_schedules->all());
                }
            }

            return self::schedulesFilter($schedules, $dateStart, $dateFin, false, $orderColumn, $order, $dateFormat);
        }

        return null;
    }

    public static function schedulesFilter(
        $schedules,
        $dateStart = null,
        $dateFin = null,
        $strict = false,
        $orderColumn = 'start_date',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        $order = in_array(strtolower($order), ['asc', 'desc']) ? $order : 'DESC';
        $orderColumn = in_array($orderColumn, ['start_date', 'end_date']) ? $orderColumn : 'start_date';

        $schedules = collect($schedules);

        if ($dateStart) {
            $date = $strict ? $dateStart : $dateStart . ' 00:00';
            $dateStart = Carbon::createFromFormat($dateFormat, $date)->getTimestamp();
        }

        if ($dateFin) {
            $date = $strict ? $dateFin : $dateFin . ' 23:59';
            $dateFin = Carbon::createFromFormat($dateFormat, $date)->getTimestamp();
        }

        $schedules = $schedules->filter(function ($value, $key) use ($dateStart, $dateFin) {
            $result = true;
            $timestamp = $value->start_date->getTimestamp();

            if ($dateStart) {
                $result = $timestamp >= $dateStart;
            }

            if ($result && $dateFin) {
                $result = $timestamp <= $dateFin;
            }

            return $result;
        });

        return $order == 'DESC'
            ? $schedules->sortByDesc($orderColumn)
            : $schedules->sortBy($orderColumn);
    }
}
