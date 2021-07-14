@extends(layouts.app)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex">
                    @if ($user->id == Auth::user()->id)
                        My profile
                        <a href="{{ route('profile.edit') }}" class="btn btn-success ml-auto">
                            <i class="fa fa-pen"></i>
                            Edit
                        </a>
                    @else
                    {{ __('Profile of ') . $user->full_name }}
                    @endif
                </div>

                <div class="card-body">
                    Type : {{ $user->type }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
