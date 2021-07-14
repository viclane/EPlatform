<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    /**
     * @var CourseRepository
     */
    private $CourseRepo;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->middleware(['auth', 'is_student']);
        $this->courseRepo = $courseRepo;
    }

    public function index(Request $request)
    {
        return $this->view('students.index', [
            'courses_count' => count($request->user()->courses)
        ]);
    }

    public function show_course(Request $request, Course $course)
    {
        return view('students.showCourse', [
            'course' => $course
        ]);
    }

    public function show_my_courses(Request $request)
    {
        $courses = $request->user()->courses();

        if ($query_string = $request->get('query')) {
            $courses = $courses->where('title', 'LIKE', '%' . $query_string . '%');
        }

        return view('students.courses', [
            'query' => $query_string,
            'courses' => $courses->paginate(20)
        ]);
    }

    public function show_all_courses(Request $request)
    {
        $unsubscribe = $request->get('unsubscribe');

        if ($unsubscribe == 1) {
            $courses = $this->courseRepo->getOthersFormationCourses($request->user()->formation_id);
        } else {
            $courses = $this->courseRepo->getFormationCourses($request->user()->formation_id);
        }

        if ($query_string = $request->get('query')) {
            $courses = $courses->where('title', 'LIKE', '%' . $query_string . '%');
        }

        return view('students.showFormCourses', [
            'unsubscribe' => $unsubscribe ? true : false,
            'query' => $query_string,
            'courses' => $courses->paginate(20)
        ]);
    }

    public function update_course(Request $request, Course $course)
    {
        $request->validate([
            'validate' => 'required'
        ]);

        $validate = $request->get('validate');
        if ($validate == 0) {
            $course->students()->detach($request->user()->id);
            $message = 'unsubscribe successfully';
        } elseif ($validate == 1) {
            $course->students()->attach($request->user()->id);
            $message = 'subscribe successfully';
        }

        if ($course->save()) {
            return redirect()->back()->with(['success' => $message]);
        }

        return redirect()->back()->with(['error' => 'An unexpected error has occurred']);
    }

    public function show_schedules(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => array('nullable', 'date', $start_date && $end_date? 'after_or_equal:start_date' : '')
        ]);

        $courses = $request->user()->courses;

        $active_course = $request->get('course_id');
        if ($active_course) {
            $active_course = Course::find($active_course);
            if (!$active_course || !in_array($active_course->id, $courses->pluck('id')->toArray())) {
                $active_course = null;
            }
        }

        $schedules = [];

        if ($active_course) {
            $schedules = $active_course->schedules->all();
        } else {
            foreach ($courses->pluck('schedules')->all() as $course_schedules) {
                $schedules = array_merge($schedules, $course_schedules->all());
            }
        }

        return view('students.schedules', [
            'active_course' => $active_course,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'courses' => $courses,
            'schedules' => CourseRepository::schedulesFilter($schedules, $start_date, $end_date)
        ]);
    }
}
