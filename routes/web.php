<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/da', function () {
    return view('admin.dashboard');
});

Route::get('/user', function () {
    return view('admin.admin.user');
});


Auth::routes();
Route::get('/instructors/register', [\App\Http\Controllers\Auth\RegisterController::class, 'instructorForm'])->name('teachers.register');

Route::get('not_authorize', function () {
    return view('errors.401');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::redirect('/dashboard', '/home');

    Route::get('/profile/edit', [App\Http\Controllers\HomeController::class, 'showProfileForm'])->name('profile.edit');
    Route::put('/profile/edit', [App\Http\Controllers\HomeController::class, 'updateProfile']);
    Route::get('/profile/{user?}', [App\Http\Controllers\HomeController::class, 'showProfile'])->name('profile.show');

    Route::group(['middleware' => 'is_student', 'prefix' => '/students', 'as' => 'students.'], function () {
        Route::get('/home', [App\Http\Controllers\StudentController::class, 'index'])->name('index');
        Route::get('/courses', [App\Http\Controllers\StudentController::class, 'show_my_courses'])->name('courses');
        Route::get('/courses/add', [App\Http\Controllers\StudentController::class, 'show_all_courses'])->name('courses.add');
        Route::get('/courses/{course}', [App\Http\Controllers\StudentController::class, 'show_course'])->name('courses.show');
        Route::put('/courses/{course}', [App\Http\Controllers\StudentController::class, 'update_course'])->name('courses.update');
        Route::get('/schedules', [App\Http\Controllers\StudentController::class, 'show_schedules'])->name('schedules');
    });

    Route::group(['middleware' => 'is_instructor', 'prefix' => '/instructors', 'as' => 'instructors.'], function () {
        Route::get('/home', [\App\Http\Controllers\InstructorController::class, 'index'])->name('index');
        Route::get('/courses', [App\Http\Controllers\InstructorController::class, 'show_courses'])->name('courses');
        Route::get('/courses/{course}', [App\Http\Controllers\InstructorController::class, 'show_course'])->name('courses.show');
        Route::resource('schedules', App\Http\Controllers\ScheduleController::class);
    });


    Route::group(['middleware' => 'is_admin', 'prefix' => '/admin', 'as' => 'admin.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('index');
        Route::get('users/unvalidate', [\App\Http\Controllers\Admin\UserController::class, 'unvalidate'])->name('users.unvalidate');
        Route::delete('users/{user}/forceDelete', [\App\Http\Controllers\Admin\UserController::class, 'forceDelete'])->name('users.forceDelete');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);
        Route::resource('formations', \App\Http\Controllers\Admin\FormationController::class);
        Route::resource('schedules', \App\Http\Controllers\Admin\ScheduleController::class);
    });
});
