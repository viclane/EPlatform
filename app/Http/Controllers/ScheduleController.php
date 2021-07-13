<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Course;
use App\Models\Schedule;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * @var CourseRepository
     */
    private $courseRepo;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function index(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date= $request->get('end_date');

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => array('nullable', 'date', $start_date && $end_date ? 'after_or_equal:start_date' : '')
        ]);

        $active_course = $request->get('course_id');
        if ($active_course) {
            $active_course = Course::find($active_course);
            if (!$active_course || $active_course->user_id != $request->user()->id) {
                $active_course = null;
            }
        }

        return view('instructors.schedules.index', [
            'active_course' => $active_course,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'courses' => $this->courseRepo->getCourses($request->user()->id)->get(),
            'schedules' => $this->courseRepo->getUserCoursesSchedules($active_course, $start_date, $end_date)
        ]);
    }

    public function create(Request $request)
    {
        $schedule = new Schedule;

        if ($course_id = $request->get('course_id')) {
            if (Course::find($course_id)) {
                $schedule->course_id = $course_id;
            }
        }

        return view('instructors.schedules.showForm', [
            'editing' => false,
            'schedule' => $schedule,
            'courses' => $this->courseRepo->getCourses($request->user()->id)->get()
        ]);
    }

    public function store(ScheduleRequest $request)
    {
        $data = $request->all();
        $this->setGoodDate($data, $request);

        $schedule = Schedule::create($data);

        if ($schedule) {
            return redirect()->route('instructors.schedules.index')->with(['success' => 'Schedule added successfully']);
        }

        return redirect()->back()->with(['error' => 'An unexpected error has occured']);
    }

    public function show(Schedule $schedule)
    {
        return view('instructors.schedules.show', [
            'schedule' => $schedule,
        ]);
    }

    public function edit(Request $request, Schedule $schedule)
    {
        return view('instructors.schedules.showForm', [
            'editing' => true,
            'schedule' => $schedule,
            'courses' => $this->courseRepo->getCourses($request->user()->id)->get()
        ]);
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $data = $request->all();
        $this->setGoodDate($data, $request);

        if ($schedule->update($data)) {
            return redirect()->route('instructors.schedules.index')
                ->with(['success' => 'schedule successfully modified']);
        }

        return redirect()->back()->with(['error' => 'An unexpected error has occured']);
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->delete()) {
            return redirect()->route('instructors.schedules.index')
                ->with(['success' => 'schedule deleted successfully']);
        }

        return redirect()->back()->with(['error' => 'An unexpected error has occured']);
    }

    /**
     * @param array $data
     * @param ScheduleRequest $request
     */
    public function setGoodDate(array &$data, $request)
    {
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
    }}
