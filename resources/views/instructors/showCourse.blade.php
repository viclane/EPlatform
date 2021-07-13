@extends('layouts.app', ['title' => "Course display {$course->intitule}"])

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
                            <a href="{{ route('instructors.schedules.index', ['course_id' => $course->id]) }}"
                                class="btn btn-secondary btn-sm mr-1 mb-1">
                                <i class="fa fa-clock"></i>
                                See a schedule
                            </a>

                            <a href="{{ route('instructors.schedules.create', ['course_id' => $course->id]) }}"
                                class="btn btn-warning btn-sm mr-1 mb-1">
                                <i class="fa fa-plus"></i>
                                Add schedule
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        Intitule: {{ $course->intitule }}
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
                            <li class="">
                                <a href="{{ route('profile.show', ['user' => $student->id]) }}">
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
