@extends('layouts.app', ['title' => 'List of my courses'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                    List of my courses

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('instructors.schedules.create') }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Add a schedule
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Name of course</th>
                                    <th scope="col">Name of registered students</th>
                                    <th scope="col">Pass schedules</th>
                                    <th scope="col">Upcoming schedules</th>
                                    <th scope="col" style="width: 50%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->students->count() }}</td>
                                    <td>{{ $course->stat_datas['passed'] }}</td>
                                    <td>{{ $course->stat_datas['coming'] }}</td>
                                    <td class="pb-2" style="width: 50%;">
                                        <a href="{{ route('instructors.courses.show', ['course' => $course->id]) }}"
                                            class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            
                                                View
                                        </a>
                                        <a href="{{ route('instructors.schedules.index', ['course_id' => $course->id]) }}"
                                            class="btn btn-secondary btn-sm mr-1 mb-1">
                                            <i class="fa fa-clock"></i>
                                            View a schedule
                                        </a>
                                        <a href="{{ route('instructors.schedules.create', ['course_id' => $course->id]) }}"
                                            class="btn btn-warning btn-sm mr-1 mb-1">
                                            <i class="fa fa-plus"></i>
                                            Add schedule
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
