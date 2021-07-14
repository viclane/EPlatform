<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class ScheduleRequest extends BaseRequest
{
    public $start_date = '';
    public $end_date = '';

    public function rules()
    {
        return [
            'course_id' => 'nullable|exists:cours,id',
            'start_date' => array('required', 'bail', 'date_format:"d/m/Y"', 'after:yesterday'),
            'start_time' => array('required', 'bail', 'date_format:"H:i"', function ($attribute, $value, $fail) {
                if (self::isInvalideDate($this->start_date)) {
                    $fail('Invalid start date');
                } else {

                    $formDate = Carbon::createFromFormat('d/m/Y H:i', $this->start_date . ' 23:59');

                    if (!$formDate->isPast()) {
                        $formDate = Carbon::createFromFormat('d/m/Y H:i', $this->start_date . ' ' . $value);
                        $verifDate = clone $formDate;

                        if ($verifDate->addMinutes(-30)->isPast()) {
                            $fail('The time is invalid and must be at least 30 minutes ahead');
                        }

                        $schedule = Schedule::whereRaw("course_id = {$this->course_id} AND start_date <= '{$formDate->format('Y-m-d H:i')}' AND end_date > '{$formDate->format('Y-m-d H:i')}'")->first();
                        if ($schedule) {
                            $isActualSchedule = $this->route('schedule') ? $this->route('schedule')->id == $schedule->id : false;

                            if (!$isActualSchedule) {
                                $fail("The date and time belong to an interval already scheduled, starting at {$schedule->start_date} and ending at {$schedule->end_date} for this course");
                            }
                        }
                    }
                }
            }),
            'end_date' => array('nullable', 'bail', 'date', 'after_or_equal:' . $this->start_date),
            'fin_heure' => array('required', 'bail', 'date_format:"H:i"', function ($attribute, $value, $fail) {
                $date = $this->end_date ?? $this->start_date;

                if (self::isInvalideDate($this->start_date)) {
                    $fail('Invalid start date');
                } else if ($date !== $this->start_date && self::isInvalideDate($date)) {
                    $fail('Invalid end date');
                } else {
                    $startDate = Carbon::createFromFormat('d/m/Y H:i', $this->start_date . ' ' . $this->start_time);
                    $formDate = Carbon::createFromFormat('d/m/Y H:i', $date . ' 23:59');

                    if (!$formDate->isPast()) {
                        $formDate = Carbon::createFromFormat('d/m/Y H:i', $date . ' ' . $value);
                        if ($formDate->getTimestamp() < $startDate->getTimestamp()) {
                            $fail('La date et l\'heure de end doivent etre superieur a la date de start');
                        }
                        if (($formDate->getTimestamp() - $startDate->getTimestamp()) < 1200) {
                            $fail('La date et l\'heure de end doivent etre superieur a la date de start d\'au moins 20 minutes');
                        }

                        $schedule = Schedule::whereRaw("course_id = {$this->course_id} AND start_date < '{$formDate->format('Y-m-d H:i')}' AND end_date >= '{$formDate->format('Y-m-d H:i')}'")->first();
                        if ($schedule) {
                            $isActualSchedule = $this->route('schedule') ? $this->route('schedule')->id == $schedule->id : false;

                            if (!$isActualSchedule) {
                                $fail("La date et l'heure appartiennent a un intervalle deja planifie, commencant a {$schedule->start_date} pour ce cours");
                            }
                        }

                        $this->start_date = $startDate->format('Y-m-d H:i:s');
                        $this->end_date = $formDate->format('Y-m-d H:i:s');
                    }
                }
            }),
        ];
    }

    public static function isInvalideDate ($date, $format = 'd/m/Y') {
        if (! is_string($date)) {
            return true;
        }

        if ($newDate = \DateTime::createFromFormat($format, $date)) {
            return $newDate->format($format) !== $date;
        }

        return true;
    }
}
