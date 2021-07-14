{{-- Create a formation --}}
@php
    if ($editing) {
        \Illuminate\Support\Facades\Request::merge($formation->toArray());
        \Illuminate\Support\Facades\Request::flash();
    }
@endphp

@extends('layouts.app', ['title' => 'Add un utilisateur'])

@section('content')

    <div class="container">
        <form action="{{ $editing ? route('admin.formations.update', ['formation' => $formation->id]) : route('admin.formations.store') }}" method="post">
            @csrf
            @if($editing)
                @method('put')
            @endif

            <div class="row">
                <div class="col-md-8 {{ $editing ? '' : 'mx-auto' }}">
                    <div class="card">
                        <div class="card-header">
                            {{ $editing ? 'Edit the formation' : 'Create the formation' }}
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $formation->title) }}"
                                    class="form-control @error('title') is-invalid @enderror" placeholder="Title" required>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Add courses in the formation
                        </div>
                        <div class="card-body">
                            @include('admin.partials.addCourses', ['item' => $formation])
                            <div class="mb-2">
                                <small>If the course does not exist, <strong><a href="{{ route('admin.courses.create', ['formation_id' => $formation->id]) }}">Add here</a></strong></small>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
