@extends('layouts.app', ['title' => 'List of my schedules'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        List of my schedules
                        @if ($active_course)
                         du cours {{ $active_course->intitule }}
                        @endif

                        <div class="col-md-6 ml-auto">
                            <form action="" method="get" class="form-inline">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" placeholder="dd/mm/yyyy" id="start_date" name="start_date"
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
                                    <input type="date" placeholder="dd/mm/yyyy" id="end_date" name="end_date"
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
                                        <option value="" @if($active_course == null) selected @endif>All $courses</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" @if($active_course && $active_course->id == $course->id) selected @endif>
                                                {{ $course->intitule }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Name of course</th>
                                    <th scope="col">Start date</th>
                                    <th scope="col">End date</th>
                                    <th scope="col" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->course->intitule }}</td>
                                    <td>{{ $schedule->date_start }}</td>
                                    <td>{{ $schedule->date_fin }}</td>
                                    <td class="pb-2" style="width: 25%;">
                                        <a href="{{ route('students.courses.show', ['course' => $schedule->course->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                            <i class="fa fa-eye"></i>
                                            Voir le cours
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
