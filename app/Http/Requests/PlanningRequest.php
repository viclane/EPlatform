<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Planning;
use App\Models\User;
use Carbon\Carbon;

class PlanningRequest extends BaseRequest
{
    public $date_debut = '';
    public $date_fin = '';

    public function rules()
    {
        return [
            'cours_id' => 'nullable|exists:cours,id',
            'debut_date' => array('required', 'bail', 'date_format:"d/m/Y"', 'after:yesterday'),
            'debut_heure' => array('required', 'bail', 'date_format:"H:i"', function ($attribute, $value, $fail) {
                if (self::isInvalideDate($this->debut_date)) {
                    $fail('Date de debut invalide');
                } else {

                    $formDate = Carbon::createFromFormat('d/m/Y H:i', $this->debut_date . ' 23:59');

                    if (!$formDate->isPast()) {
                        $formDate = Carbon::createFromFormat('d/m/Y H:i', $this->debut_date . ' ' . $value);
                        $verifDate = clone $formDate;

                        if ($verifDate->addMinutes(-30)->isPast()) {
                            $fail('Le temps est invalide et doit etre en avance d\'au moins 30 minutes');
                        }

                        $planning = Planning::whereRaw("cours_id = {$this->cours_id} AND date_debut <= '{$formDate->format('Y-m-d H:i')}' AND date_fin > '{$formDate->format('Y-m-d H:i')}'")->first();
                        if ($planning) {
                            $isActualPlanning = $this->route('planning') ? $this->route('planning')->id == $planning->id : false;

                            if (!$isActualPlanning) {
                                $fail("La date et heure appartiennent a un intervalle deja planifie, commencant a {$planning->date_debut} et finissant a {$planning->date_fin} pour ce cours");
                            }
                        }
                    }
                }
            }),
            'fin_date' => array('nullable', 'bail', 'date', 'after_or_equal:' . $this->debut_date),
            'fin_heure' => array('required', 'bail', 'date_format:"H:i"', function ($attribute, $value, $fail) {
                $date = $this->fin_date ?? $this->debut_date;

                if (self::isInvalideDate($this->debut_date)) {
                    $fail('Date de debut invalide');
                } else if ($date !== $this->debut_date && self::isInvalideDate($date)) {
                    $fail('Date de fin invalide');
                } else {
                    $debutDate = Carbon::createFromFormat('d/m/Y H:i', $this->debut_date . ' ' . $this->debut_heure);
                    $formDate = Carbon::createFromFormat('d/m/Y H:i', $date . ' 23:59');

                    if (!$formDate->isPast()) {
                        $formDate = Carbon::createFromFormat('d/m/Y H:i', $date . ' ' . $value);
                        if ($formDate->getTimestamp() < $debutDate->getTimestamp()) {
                            $fail('La date et l\'heure de fin doivent etre superieur a la date de debut');
                        }
                        if (($formDate->getTimestamp() - $debutDate->getTimestamp()) < 1200) {
                            $fail('La date et l\'heure de fin doivent etre superieur a la date de debut d\'au moins 20 minutes');
                        }

                        $planning = Planning::whereRaw("cours_id = {$this->cours_id} AND date_debut < '{$formDate->format('Y-m-d H:i')}' AND date_fin >= '{$formDate->format('Y-m-d H:i')}'")->first();
                        if ($planning) {
                            $isActualPlanning = $this->route('planning') ? $this->route('planning')->id == $planning->id : false;

                            if (!$isActualPlanning) {
                                $fail("La date et l'heure appartiennent a un intervalle deja planifie, commencant a {$planning->date_debut} pour ce cours");
                            }
                        }

                        $this->date_debut = $debutDate->format('Y-m-d H:i:s');
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
