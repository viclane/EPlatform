@extends('layouts.app', ['title' => "Affichage du schedule du cours {$schedule->course->title}"])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        Details du schedule

                        <a href="{{ route('admin.schedules.edit', ['schedule' => $schedule->id]) }}" class="ml-auto btn btn-primary">
                            <i class="fa fa-pen"></i>
                            Editer
                        </a>
                    </div>
                    <div class="card-body">
                        Start date: {{ $schedule->start_date }}
                        <br/>
                        End date: {{ $schedule->end_date }}
                        <br/>
                        Cours:
                        <a href="{{ route('admin.courses.show', ['course' => $schedule->course->id]) }}">
                            {{ $schedule->course->title }}
                        </a>
                        <br/>
                        instructor:
                        @if($schedule->course->instructor)
                            <a href="{{ route('profile.show', ['user' => $schedule->course->instructor->id]) }}">
                                {{ $schedule->course->instructor->full_name }}
                            </a>
                        @else
                            Non assigné
                        @endif
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
