@extends('Layourt.LinkCss')
@section('title')
    bonjour
@endsection
@section('header')
    @include('Layourt.Navbar')
@endsection
@section('content')
<form><br><br>
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="border: 1px solid black; background-color: darkcyan; opacity: 1;">
                <form>
                    <div class="form-group"><br>
                      <label for="exampleInputEmail1">Email address</label>
                      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <div class="form-group form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button><br>
                  </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div><br><br><br>
    
@endsection

@section('footer')
    @include('Layourt.Footer')
@endsection