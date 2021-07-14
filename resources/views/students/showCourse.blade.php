@extends('layouts.app', ['title' => "Display courses {$course->title}"])

@php
    $myCourses = Auth::user()->courses->pluck('id')->toArray();
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Course details

                        <div class="ml-auto">
                            <form action="{{ route('students.courses.update', ['course' => $course->id]) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('PUT')


                                @if (in_array($course->id, $myCourses))
                                    <input type="hidden" name="validate" value="0">
                                    <button type="submit" class="btn btn-danger btn-sm mr-1 mb-1">
                                        <i class="fa fa-power-off"></i>
                                      To unsubscribe
                                    </button>
                                @else
                                    <input type="hidden" name="validate" value="1">
                                    <button type="submit" class="btn btn-warning btn-sm mr-1 mb-1">
                                        <i class="fa fa-sign-in-alt"></i>
                                       Register
                                    </button>
                                @endif
                            </form>
                        </div>

                        @if (in_array($course->id, $myCourses))
                            <div class="ml-auto">
                                <a href="{{ route('students.schedules', ['course_id' => $course->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                    <i class="fa fa-clock"></i>
                                    View a schedule
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        Title: {{ $course->title }}
                        <br/>
                        Instructor: @if($course->instructor)
                        <a href="{{ route('profile.show', ['user' => $course->instructor->id]) }}">
                            {{ $course->instructor->full_name }}
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
                            @if ($student->id != Auth::user()->id)
                            <li class="">
                                <a href="{{ route('profile.show', ['user' => $student->id]) }}">
                                    {{ $student->full_name }}
                                </a>
                            </li>
                            @endif
                        @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
