<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\CourseRepository;
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
        $start_date = $request->get('start_date ');
        $end_date = $request->get('end_date');

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => array('nullable', 'date', $start_date && $end_date ? 'after_or_equal:start_date ' : '')
        ]);

        $active_course = $request->get('course_id');
        $active_course = $active_course ? Course::find($active_course) : null;

        $active_user = $request->get('user_id');
        $schedules = $active_user
            ? $this->courseRepo->getUserCoursesSchedules($active_course, $start_date , $end_date, User::find($active_user))
            : $this->courseRepo->getCoursesSchedules($active_course, $start_date , $end_date);


        return view('admin.schedules.index', [
            'active_user' => $active_user,
            'active_course' => $active_course,
            'start_date ' => $start_date,
            'end_date' => $end_date,
            'courses' => Course::all(),
            'schedules' => $schedules
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

        return view('admin.schedules.showForm', [
            'editing' => false,
            'schedule' => $schedule,
            'courses' => Course::all()
        ]);
    }

    public function store(ScheduleRequest $request)
    {
        $data = $request->all();
        $this->setGoodDate($data, $request);

        if (Schedule::create($data)) {
            return redirect()->route('admin.schedules.index')->with(['success' => 'Schedule has been added !']);
        }

        return redirect('/', 500)->with(['error' => 'An unexpected error has occured']);
    }

    public function show(Schedule $schedule)
    {
        return view('admin.schedules.show', ['schedule' => $schedule]);
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.showForm', [
            'editing' => true,
            'schedule' => $schedule,
            'courses' => Course::all()
        ]);
    }

    public function update(ScheduleRequest $request,Schedule $schedule)
    {
        $data = $request->all();
        $this->setGoodDate($data, $request);

        if ($schedule->update($data)) {
            return redirect()->route('admin.schedules.index')
                ->with(['success' => 'Schedule has been modified successfully']);
        }

        return redirect('/', 500)->with(['error' => 'An unexpected error occured']);
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->delete()) {
            return redirect()->route('admin.schedules.index')
                ->with(['success' => 'Schedule has been deleted successfully']);
        }

        return redirect('/', 500)->with(['error' => 'An unexpected error has occured']);
    }

    /**
     * @param array $data
     * @param ScheduleRequest $request
     */
    public function setGoodDate(array &$data, $request)
    {
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
    }
}
