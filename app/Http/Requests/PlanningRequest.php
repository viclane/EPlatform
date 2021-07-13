<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class ScheduleRequest extends BaseRequest
{
    public $date_start = '';
    public $date_fin = '';

    public function rules()
    {
        return [
            'course_id' => 'nullable|exists:cours,id',
            'start_date' => array('required', 'bail', 'date_format:"d/m/Y"', 'after:yesterday'),
            'start_heure' => array('required', 'bail', 'date_format:"H:i"', function ($attribute, $value, $fail) {
                if (self::isInvalideDate($this->start_date)) {
                    $fail('Date de start invalide');
                } else {

                    $formDate = Carbon::createFromFormat('d/m/Y H:i', $this->start_date . ' 23:59');

                    if (!$formDate->isPast()) {
                        $formDate = Carbon::createFromFormat('d/m/Y H:i', $this->start_date . ' ' . $value);
                        $verifDate = clone $formDate;

                        if ($verifDate->addMinutes(-30)->isPast()) {
                            $fail('Le temps est invalide et doit etre en avance d\'au moins 30 minutes');
                        }

                        $schedule = Schedule::whereRaw("course_id = {$this->course_id} AND date_start <= '{$formDate->format('Y-m-d H:i')}' AND date_fin > '{$formDate->format('Y-m-d H:i')}'")->first();
                        if ($schedule) {
                            $isActualSchedule = $this->route('schedule') ? $this->route('schedule')->id == $schedule->id : false;

                            if (!$isActualSchedule) {
                                $fail("La date et heure appartiennent a un intervalle deja planifie, commencant a {$schedule->date_start} et finissant a {$schedule->date_fin} pour ce cours");
                            }
                        }
                    }
                }
            }),
            'end_date' => array('nullable', 'bail', 'date', 'after_or_equal:' . $this->start_date),
            'fin_heure' => array('required', 'bail', 'date_format:"H:i"', function ($attribute, $value, $fail) {
                $date = $this->end_date ?? $this->start_date;

                if (self::isInvalideDate($this->start_date)) {
                    $fail('Date de start invalide');
                } else if ($date !== $this->start_date && self::isInvalideDate($date)) {
                    $fail('Date de end invalide');
                } else {
                    $startDate = Carbon::createFromFormat('d/m/Y H:i', $this->start_date . ' ' . $this->start_heure);
                    $formDate = Carbon::createFromFormat('d/m/Y H:i', $date . ' 23:59');

                    if (!$formDate->isPast()) {
                        $formDate = Carbon::createFromFormat('d/m/Y H:i', $date . ' ' . $value);
                        if ($formDate->getTimestamp() < $startDate->getTimestamp()) {
                            $fail('La date et l\'heure de end doivent etre superieur a la date de start');
                        }
                        if (($formDate->getTimestamp() - $startDate->getTimestamp()) < 1200) {
                            $fail('La date et l\'heure de end doivent etre superieur a la date de start d\'au moins 20 minutes');
                        }

                        $schedule = Schedule::whereRaw("course_id = {$this->course_id} AND date_start < '{$formDate->format('Y-m-d H:i')}' AND date_fin >= '{$formDate->format('Y-m-d H:i')}'")->first();
                        if ($schedule) {
                            $isActualSchedule = $this->route('schedule') ? $this->route('schedule')->id == $schedule->id : false;

                            if (!$isActualSchedule) {
                                $fail("La date et l'heure appartiennent a un intervalle deja planifie, commencant a {$schedule->date_start} pour ce cours");
                            }
                        }

                        $this->date_start = $startDate->format('Y-m-d H:i:s');
                        $this->date_fin = $formDate->format('Y-m-d H:i:s');
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
