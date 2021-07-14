{{-- Create a user --}}
@php
    if ($editing) {
        \Illuminate\Support\Facades\Request::merge($user->toArray());
        \Illuminate\Support\Facades\Request::flash();
    }
@endphp

@extends('layouts.app', ['title' => $editing ? 'Edit un utilisateur' : 'Add un utilisateur'])

@section('content')

    <form action="{{ $editing ? route('admin.users.update', ['user' => $user->id]) : route('admin.users.store') }}" method="post">
        @csrf

        @if($editing)
            @method('put')
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 {{ $user->is_instructor ? '' : 'mx-auto' }}">
                    <div class="card">
                        <div class="card-header">
                            {{ $editing ? 'Edit '.$user->full_name : 'Add user' }}
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="first_name">Fisrt_name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last_name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input type="email" name="login" id="login" value="{{ old('login', $user->login) }}" class="form-control @error('login') is-invalid @enderror" required>

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="type">
                                    {{ __('Type') }}
                                </label>

                                <select name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror">
                                    <option value="" @if(!old('type', $user->type) && !$user->formation_id) selected @endif disabled>Choisir un type</option>
                                    <option value="admin" @if(old('type', $user->type) == 'admin') selected @endif>
                                        Admin
                                    </option>
                                    <option value="student" @if(old('type', $user->type) == 'student' || $user->formation_id) selected @endif>
                                        student
                                    </option>
                                    <option value="instructor" @if(old('type', $user->type) == 'instructor') selected @endif>
                                        instructor
                                    </option>
                                </select>

                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="formation_id">
                                    {{ __('Formation') }}
                                    <small>
                                        (Only select if the user is a student)
                                    </small>
                                </label>

                                <select name="formation_id" id="formation_id"
                                    class="form-control @error('formation_id') is-invalid @enderror">
                                    <option value=""
                                        @if(old('formation_id', $user->formation_id) == null) selected @endif>
                                       No formation
                                    </option>
                                    @foreach($formations as $formation)
                                    <option value="{{ $formation->id }}"
                                        @if(old('formation_id', $user->formation_id) == $formation->id) selected @endif>
                                        {{ $formation->title }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('formation_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" @if (!$editing) required @endif>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" @if (!$editing) required @endif>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-success" type="submit">
                                    {{ $editing ? 'Edit' : 'Add' }}
                                </button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($user->is_instructor || $user->is_student)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ $user->is_student ? 'Enroll the student in courses ' : 'Add courses to instructor' }}
                        </div>
                        <div class="card-body">
                            @include('admin.partials.addCourses', [
                                'item' => $user,
                                'courses' => $user->formation_id ? $user->formation->courses : $courses
                            ])

                            <div class="mb-2">
                                <small>If the course does not exist,
                                    <strong>
                                        <a href="{{ route('admin.courses.create', ['user_id' => $user->id]) }}">
                                            Add here
                                        </a>
                                    </strong>
                                </small>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
@endsection
