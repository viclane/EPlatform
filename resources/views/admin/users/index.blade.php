@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header d-flex">
                    @php
                    $title = 'User list';
                    $title = $type ? $type == 'instructor' ? 'Instructor list' : 'Students list' : $title;
                    @endphp

                    {{ $title }}


                    <div class="col-md-6 ml-auto">
                        <form action="" method="get" class="form-inline">
                            <input type="text" name="query" class="form-control mr-2 mb-2" value="{{ old('query', $query ?? '') }}"/>
                            <div class="form-group mr-2 mb-2">
                                <select class="form-control" name="type">
                                    <option value="" @if($type == null) selected @endif>Everybody</option>
                                    <option value="instructor" @if($type == 'instructor') selected @endif>
                                        Instructors
                                    </option>
                                    <option value="student" @if($type == 'student') selected @endif>
                                        Students
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                        </form>
                    </div>

                    <div class="ml-auto">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
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
                                <th scope="col">Name</th>
                                <th scope="col">Login</th>
                                <th scope="col">Role</th>
                                <th scope="col">Formation</th>
                                <th scope="col">Number of course</th>
                                <th scope="col" style="width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->login }}</td>
                                <td>{{ $user->type ? $user->type : 'null' }}</td>
                                <td>{{ $user->formation ? $user->formation->title : '' }}</td>
                                <td>{{ $user->courses && !$user->is_admin ? $user->courses->count() : '0' }}</td>
                                <td class="pb-2" style="width: 25%;">
                                    <a href="{{ route('admin.users.show', ['user' => $user->id]) }}" class="btn btn-primary btn-sm mr-1 mb-1">
                                        <i class="fa fa-eye"></i>
                                        View
                                    </a>
                                    <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}" class="btn btn-secondary btn-sm mr-1 mb-1">
                                        <i class="fa fa-pen"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.users.forceDelete', ['user' => $user->id]) }}" method="POST"
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
