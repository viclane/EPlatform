@extends('layouts.app', ['title' => 'Courses list'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Courses list
                        @if ($active_instructor == 'no')
                         Not assigned
                        @elseif ($active_instructor)
                         de {{ $active_instructor->full_name }}
                        @endif

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                                <div class="form-group mr-2 mb-2">
                                    <select class="form-control" name="instructor_id">
                                        <option value="" @if($active_instructor == null) selected @endif>All instructors</option>
                                        <option value="no" @if($active_instructor == 'no') selected @endif>Not assigned </option>
                                        @foreach ($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" @if($active_instructor && $active_instructor!= 'no' && $active_instructor->id == $instructor->id) selected @endif>
                                                {{ $instructor->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Add
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Assigned instructor</th>
                                    <th scope="col">Number of students</th>
                                    <th scope="col">Formation title</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td class="{{ $course->instructor ? 'text-primary' : 'text-danger' }}">
                                        @if($course->instructor)
                                        <a href="{{ route('admin.users.show', ['user' => $course->instructor->id]) }}">
                                            {{ $course->instructor->full_name }}
                                        </a>
                                        @else
                                            Not Assigned
                                        @endif
                                    </td>
                                    <td>{{ $course->students->count() }}</td>
                                    <td class="{{ $course->formation ? 'text-success' : 'text-danger' }}">
                                        {{ $course->formation ? $course->formation->title : 'Not assigned' }}
                                    </td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('admin.courses.show', ['course' => $course->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            View
                                        </a>
                                        <a href="{{ route('admin.schedules.index', ['course_id' => $course->id]) }}" class="btn btn-outline-danger btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            View a schedule
                                        </a>
                                        <a href="{{ route('admin.courses.edit', ['course' => $course->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                            <i class="fa fa-pen"></i>
                                            Modify
                                        </a>
                                        <form action="{{ route('admin.courses.destroy', ['course' => $course->id]) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mr-1 mb-1">
                                                <i class="fa fa-trash-alt"></i>
                                                Delete
                                            </button>
                                        </form>
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
