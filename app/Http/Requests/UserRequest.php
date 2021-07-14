<?php

namespace App\Http\Requests;

use App\Models\Course;

class UserRequest extends BaseRequest
{

    protected $courses_array = [];
    protected $active_user = null;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->sometimes == '' ? null : $this->route('user');
        $this->active_user = $user;
        $unique_login = 'unique:users';
        $unique_login .= $user ? ',login,' . $user->id : '';

        return [
            'fisrt_name' => $this->sometimes . 'required|string|max:40',
            'last_name' => $this->sometimes . 'required|string|max:40',
            'login' => $this->sometimes . 'required|email|' . $unique_login,
            'formation_id' => ['required_if:type,student'],
            'password' => [
                $this->sometimes == '' ? 'required' : 'nullable',
                'confirmed'
            ],
            'courses' => ['nullable', function ($attribute, $value, $fail) use ($user) {
                foreach ($value as $item) {
                    $course = Course::find($item);
                    if (!$course) {
                        $fail('One or more courses non-existent');
                    } else if ($course->user_id && (!$user || ($user->is_instructor && $course->user_id != $user->id))) {
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
        if ($this->active_user) {
            $user_courses = $this->active_user->courses()->pluck('id')->toArray();
            return array_filter($this->courses_array, function ($course) use ($user_courses) {
                return !in_array($course->id, $user_courses);
            });
        }

        return [];
    }
}
