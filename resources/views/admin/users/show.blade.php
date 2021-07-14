@extends('layouts.app', ['title' => 'Profile of' . $user->full_name])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        User details

                        <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}" class="ml-auto btn btn-primary">
                            <i class="fa fa-pen"></i>
                            Edit
                        </a>
                    </div>
                    <div class="card-body">
                        Fisrt_name: {{ $user->first_name }}
                        <br>
                        Last_name: {{ $user->last_name }}
                        <br>
                        Login: {{ $user->login }}
                        <br>
                        Type: {{ $user->type }}
                        <br>
                        @if ($user->is_student && $user->formation)
                        Formation: <a href="{{ route('admin.formations.show', ['formation' => $user->formation->id]) }}">{{ $user->formation->title }}</a>
                        @endif

                        <br/>

                        @php
                            $count = $user->courses->count();
                        @endphp
                        @if ($count && ($user->is_student || $user->is_instructor))
                            <p>
                                @if ($user->is_student)
                                is registered at
                                @else
                                is assigned at
                                @endif
                                {{ $count }} course
                            </p>
                            <ul>
                            @foreach ($user->courses as $course)
                                <li class="">
                                    <a href="{{ route('admin.courses.show', ['course' => $course->id]) }}">
                                        {{ $course->title }}
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
