<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\User;

class CourseRequest extends BaseRequest
{
    protected $students_array = [];
    protected $active_course = null;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $course = $this->sometimes == '' ? null : $this->route('course');
        $this->active_course = $course;
        $formation_id = $this->formation_id;
        $course_id = $course ? $course->id : null;

        return [
            'formation_id' => 'nullable|exists:formations,id',
            'user_id' => ['nullable', 'exists:users,id', function ($attribute, $value, $fail) {
                $user = User::find($value);
                if ($user && !$user->is_instructor) {
                    $fail('L\'utilisateur renseigne n\'est pas un instructor');
                }
            }],
            'intitule' => ['required', 'string', function ($attribute, $value, $fail) use ($formation_id, $course_id) {
                $course = Course::where('intitule', $value)
                    ->where('formation_id', $formation_id)->first();

                if ($course && $course->id != $course_id) {
                    $fail('Ce cours existe deja dans cette formation');
                }
            }],
            'students' => ['nullable', function ($attribute, $value, $fail) use ($course) {
                foreach ($value as $item) {
                    $student = User::find($item);
                    if (!$student) {
                        $fail('Un ou plusieurs students inexistant(s)');
                    } else if (!$student->is_student || !$student->formation_id || ($student->formation_id != $course->formation_id)) {
                        $fail('Certains students ne peuvent avoir acces a ce cours');
                    } else {
                        $this->students_array[] = $student;
                    }
                }
            }]
        ];
    }


    public function getNewUsers()
    {
        if ($this->active_course) {
            $course_students = $this->active_course->students()->pluck('id')->toArray();
            return array_filter($this->students_array, function ($course) use ($course_students) {
                return !in_array($course->id, $course_students);
            });
        }

        return $this->students_array;
    }
}
