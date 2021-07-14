@extends('layouts.app', ['title' => 'Schedule list'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Schedule list

                        @if ($active_user)
                            de {{ $active_user->full_name }}
                        @endif

                        @if ($active_course)
                            of course {{ $active_course->title }}
                        @endif

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" placeholder="yyyy/mm/dd" id="start_date" name="start_date"
                                        class="form-control mx-2 mb-2 @error('start_date') is-invalid @endif"
                                        value="{{ old('start_date', $start_date ?? '') }}"/>
                                    @error('end_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" placeholder="yyyy/mm/dd" id="end_date" name="end_date"
                                        class="form-control mx-2 mb-2  @error('end_date') is-invalid @endif"
                                        value="{{ old('end_date', $end_date ?? '') }}"/>
                                    @error('end_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mr-2 mb-2">
                                    <select class="form-control" name="course_id">
                                        <option value="" @if($active_course == null) selected @endif>All courses</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" @if($active_course && $active_course->id == $course->id) selected @endif>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>

                        <div class="ml-auto">
                            <a href="{{ route('admin.schedules.create') }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus"></i>
                                Add schedule
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Start date</th>
                                    <th scope="col">End date</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->course->title }}</td>
                                    <td>{{ $schedule->start_date }}</td>
                                    <td>{{ $schedule->end_date }}</td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('admin.schedules.show', ['schedule' => $schedule->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            View
                                        </a>
                                        <a href="{{ route('admin.schedules.edit', ['schedule' => $schedule->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                            <i class="fa fa-pen"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.schedules.destroy', ['schedule' => $schedule->id]) }}" method="POST"
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
