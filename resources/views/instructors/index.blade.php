@extends('layouts.app', ['title' => 'Dashboard Student'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Dashboard
                    </div>

                    <div class="card-body">
                        @include('partials.alerts')

                        <div class="container">
                            <div class="row justify-content-around">
                                <div class="col-md-3 card">
                                    <div class="card-body bg-primary">
                                        <p class="text-white text-bold">
                                            <strong class="text-h6">{{ $courses_count }}</strong>
                                            Course
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3 card">
                                    <div class="card-body bg-success">
                                        <p class="text-white text-bold">
                                            <strong class="text-h6">{{ $passed_count }}</strong>
                                            Past schedules
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3 card">
                                    <div class="card-body bg-danger">
                                        <p class="text-white text-bold">
                                            <strong class="text-h6">{{ $programmed_count }}</strong>
                                           Schedules planned
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
