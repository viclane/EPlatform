<?php

namespace App\Repositories;


use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Carbon as CarbonInterface;

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
        $startDate = null,
        $endDate = null,
        $orderColumn = 'start_date',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        if (!request()->user()->is_admin) {
            abort(500);
        }

        if ($course) {
            if (! ($course instanceof Course)) {
                throw new \Exception('Invalid course');
            }

            $schedules = $course->schedules->all();
        } else {
            $schedules = Schedule::all();
        }

        return self::schedulesFilter($schedules, $startDate, $endDate, false, $orderColumn, $order, $dateFormat);
    }

    public function getUserCoursesSchedules(
        $course = null,
        $startDate = null,
        $endDate = null,
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
                if (! ($course instanceof Course)) {
                    throw new \Exception('Invalid course');
                }

                $schedules = $course->schedules->all();
            } else {
                foreach ($user->courses->pluck('schedules')->all() as $course_schedules) {
                    $schedules = array_merge($schedules, $course_schedules->all());
                }
            }

            return self::schedulesFilter($schedules, $startDate, $endDate, false, $orderColumn, $order, $dateFormat);
        }

        return null;
    }

    public static function schedulesFilter(
        $schedules,
        $startDate = null,
        $endDate = null,
        $strict = false,
        $orderColumn = 'start_date',
        $order = 'DESC',
        $dateFormat = 'Y-m-d H:i'
    ) {
        $order = in_array(strtolower($order), ['asc', 'desc']) ? $order : 'DESC';
        $orderColumn = in_array($orderColumn, ['start_date', 'end_date']) ? $orderColumn : 'start_date';

        $schedules = collect($schedules);

        if ($startDate) {
            $date = $strict ? $startDate : $startDate . ' 00:00';
            $startDate = Carbon::createFromFormat($dateFormat, $date)->getTimestamp();
        }

        if ($endDate) {
            $date = $strict ? $endDate : $endDate . ' 23:59';
            $endDate = Carbon::createFromFormat($dateFormat, $date)->getTimestamp();
        }

        $schedules = $schedules->filter(function ($schedule, $key) use ($startDate, $endDate) {
            $result = true;
            $timestamp = null;

            if ($schedule->start_date instanceof CarbonInterface) {
                $timestamp = $schedule->start_date->getTimestamp();
            }

            if ($timestamp && $startDate) {
                $result = $timestamp >= $startDate;
            }

            if ($timestamp && $result && $endDate) {
                $result = $timestamp <= $endDate;
            }

            return $result;
        });

        return $order == 'DESC'
            ? $schedules->sortByDesc($orderColumn)
            : $schedules->sortBy($orderColumn);
    }
}
