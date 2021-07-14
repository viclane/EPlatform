<?php

namespace App\Http\Requests;

use App\Models\Course;

class FormationRequest extends BaseRequest
{
    protected $courses_array = [];
    protected $active_formation = null;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $formation = $this->sometimes == '' ? null : $this->route('formation');
        $this->active_formation = $formation;
        $unique_intitule = 'unique:formations';
        $unique_intitule .= $formation ? ',title,' . $formation->id : '';

        return [
            'title' => $this->sometimes . 'required|string|' . $unique_intitule,
            'courses' => ['nullable', function ($attribute, $value, $fail) use ($formation) {
                foreach ($value as $item) {
                    $course = Course::find($item);
                    if (!$course) {
                        $fail('One or more courses non-existent');
                    } else if ($course->formation_id && (!$formation || $course->formation_id != $formation->id)) {
                        $fail('The course has already assigned');
                    } else {
                        $this->courses_array[] = $course;
                    }
                }
            }]
        ];
    }

    public function getNewCourses()
    {
        if ($this->active_formation) {
            $formation_courses = $this->active_formation->courses()->pluck('id')->toArray();
            return array_filter($this->courses_array, function ($course) use ($formation_courses) {
                return !in_array($course->id, $formation_courses);
            });
        }

        return $this->courses_array;
    }
}
