@extends('layouts.app', ['title' => 'Add courses'])

@php
    $myCourses = Auth::user()->courses->pluck('id')->toArray();
@endphp

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Courses list

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                                <div class="form-group mr-2 mb-2">
                                    <select class="form-control" name="unsubscribe">
                                        <option value="" @if($unsubscribe == null || $unsubscribe == 0) selected @endif>All courses</option>
                                        <option value="1" @if($unsubscribe == '1') selected @endif>
                                        Not enrolled
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('students.courses') }}" class="btn btn-outline-primary">
                                <i class="fa fa-list"></i>
                                Return to the course's list
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->intitule }}</td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('students.courses.show', ['course' => $course->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                           See
                                        </a>
                                        @if (in_array($course->id, $myCourses))
                                            <a href="{{ route('students.schedules', ['course_id' => $course->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                                <i class="fa fa-clock"></i>
                                                See a schedule
                                            </a>
                                        @endif

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
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="mt-2">
                            {{ $courses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
