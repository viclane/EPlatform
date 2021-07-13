@extends('layouts.app', ['title' => "Course display {$course->title}"])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Course details

                        <div class="ml-auto">

                            <a href="{{ route('admin.schedules.create', ['course_id' => $course->id]) }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Add a schedule
                            </a>

                            <a href="{{ route('admin.courses.edit', ['course' => $course->id]) }}" class="btn btn-primary">
                                <i class="fa fa-pen"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        Title: {{ $course->title }}
                        <br/>
                        Instructor: @if($course->instructor)
                        <a href="{{ route('admin.users.show', ['user' => $course->instructor->id]) }}">
                            {{ $course->instructor->full_name }}
                        </a>
                        @else
                        Not assigned
                        @endif
                        <br/>
                        Formation: @if($course->formation)
                        <a href="{{ route('admin.formations.show', ['formation' => $course->formation->id]) }}">
                            {{ $course->formation->title }}
                        </a>
                        @else
                        Not assigned
                        @endif
                        <br/>
                        @php
                            $count = $course->students->count();
                        @endphp
                        @if ($count)
                        <p>
                            {{ $count }} students enrolled in this course
                        </p>
                        <ul>
                        @foreach ($course->students as $student)
                            <li class="">
                                <a href="{{ route('admin.users.show', ['user' => $student->id]) }}">
                                    {{ $student->full_name }}
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
