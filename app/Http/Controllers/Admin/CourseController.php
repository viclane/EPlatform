<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Formation;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request)
    {
        $courses = Course::query();
        $active_instructor= null;

        if ($query_search = $request->get('query')) {
            $courses = $courses->where('intitule', 'LIKE', "%{$query_search}%");
        }

        if ($instructor_id = $request->get('instructor_id')) {
            if ($instructor_id == 'no') {
                $courses = $courses->where('user_id', null);
                $active_instructor = 'no';
            } else {
                $courses = $courses->where('user_id', $instructor_id);
                $active_instructor = User::find($instructor_id);
            }
        }

        return view('admin.courses.index', [
            'active_instructor' => $active_instructor,
            'courses' => $courses->get(),
            'instructors' => $this->userRepo->getInstructors(),
            'query' => $query_search
        ]);
    }

    public function create(Request $request)
    {
        $course = new Course;

        if ($active_formation = Formation::find($request->get('formation_id'))) {
            $course->formation_id = $active_formation->id;
        }

        if ($active_user = User::find($request->get('user_id'))) {
            $course->user_id = $active_user->is_instructor? $active_user->id : null;
        }

        return view('admin.courses.showForm', [
            'course' => $course,
            'instructors' => $this->userRepo->getInstructors(),
            'formations' => Formation::all(),
            'editing' => false
        ]);
    }

    public function store(CourseRequest $request)
    {
        $data = $request->all();

        if ($course = Course::create($data)) {
            return redirect()->route('admin.courses.index')->with(['success' => 'The course has been added!']);
        }

        return redirect('/', 500)->with(['error' => 'An unexpected error occured']);
    }

    public function show(Course $course)
    {
        return view('admin.courses.show', ['course' => $course]);
    }

    public function edit(Course $course)
    {
        return view('admin.courses.showForm', [
            'course' => $course,
            'formations' => Formation::all(),
            'instructors' => $this->userRepo->getInstructors(),
            'editing' => true
        ]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->all();

        if ($course->update($data)) {
            $course->students()->sync($request->get('students'));
            $course->save();
            return redirect()->route('admin.courses.index')->with(['success' => 'The course has been modified !']);
        }

        return redirect('/', 500)->with(['error' => 'An unexpected error occured']);
    }

    public function destroy(Course $course)
    {
        if ($course->delete()) {
            return redirect()->route('admin.courses.index')->with(['success' => 'The course has been deleted !']);
        }

        return redirect('/', 500)->with(['error' => 'An unexpected error occured']);
    }
}
