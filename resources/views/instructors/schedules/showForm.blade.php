{{-- Create a schedule --}}
@php
    if ($editing) {
        \Illuminate\Support\Facades\Request::merge($schedule->toArray());
        \Illuminate\Support\Facades\Request::flash();
    }
@endphp

@extends('layouts.app', ['title' => $editing ? 'Edit schedule' : 'Add schedule'])

@section('content')

    <form action="{{ $editing ? route('instructors.schedules.update', ['schedule' => $schedule->id]) : route('instructors.schedules.store') }}" method="post">
        @csrf

        @if($editing)
            @method('put')
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            {{ $editing ? 'Edit schedule of '.$schedule->start_date : 'Add schedule' }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start date</label>
                                        <input type="text" placeholder="dd/mm/yyyy" name="start_date" id="start_date" value="{{ old('start_date', $schedule->start_date) }}" class="form-control @error('start_date') is-invalid @enderror" required>

                                        @error('start_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="start_time">Start time</label>
                                        <input type="text" placeholder="10:20" name="start_time" id="start_time" value="{{ old('start_time', $schedule->start_time) }}" class="form-control @error('start_time') is-invalid @enderror" required>

                                        @error('start_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">
                                            End date
                                            <small>Leave blank if the date is equivalent to the start date</small>
                                        </label>
                                        <input type="text" placeholder="dd/mm/yyyy" name="end_date" id="end_date" value="{{ old('end_date', $schedule->end_date) }}" class="form-control @error('end_date') is-invalid @enderror">

                                        @error('end_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="fin_heure">End time</label>
                                        <input type="text" placeholder="10:20" name="fin_heure" id="fin_heure" value="{{ old('fin_heure', $schedule->fin_heure) }}" class="form-control @error('fin_heure') is-invalid @enderror" required>

                                        @error('fin_heure')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <label for="course_id">
                                    {{ __('Cours') }}
                                </label>

                                <select name="course_id" id="course_id"
                                    class="form-control @error('course_id') is-invalid @enderror" required>
                                    <option value="" disabled
                                        @if(old('course_id', $schedule->course_id) == null) selected @endif>
                                        Choose a course
                                    </option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        @if(old('course_id', $schedule->course_id) == $course->id) selected @endif>
                                        {{ $course->title }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('course_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-success" type="submit">
                                    {{ $editing ? 'Edit' : 'Add' }}
                                </button>
                                @if ($editing)
                                    <a href="{{ route('instructors.schedules.index') }}" class="btn btn-secondary">
                                       To cancel
                                    </a>
                                @else
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
