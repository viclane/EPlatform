<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   
    <link rel="stylesheet" href="{{asset('bootstrap-4.3.1/dist/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('mdb/css/mdb.css')}}">
    <link rel="stylesheet"  href="{{ asset('fontawesome-free-5.11.2-web/css/all.css') }}">
    
    {{-- <link rel="stylesheet" href="{{asset('css/header.css')}}"> --}}

    <title>@yield('title')</title>
   

</head>
<body>
    {{-- ici  la fonction @yield permet de recuperer les different page que vous allez inclure--}}
     @yield('header')     


     @yield('content')


     @yield('footer')

    <script src="{{asset('bootstrap-4.3.1/dist/js/bootstrap.bundle.js')}}"></script>
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{asset('bootstrap-4.3.1/dist/js/bootstrap.js')}}"></script>
    <script src="{{asset('mdb/js/jquery.js')}}"></script>
    <script src="{{asset('mdb/js/mdb.js')}}"></script>
    {{-- <script src="{{asset('mdb/js/popper.js')}}"></script> --}}
    {{-- <script src="{{asset('js/header.js')}}"></script> --}}
</body>
</html>