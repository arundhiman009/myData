<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favion.png') }}">    
    <title>@yield('title')</title>

    @include('layouts.top')

    @yield('page-css')
</head>

<body>
    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')
    @include('layouts.footer-scripts')
    @include('layouts.flash-message')  

    @yield('page-scripts')  
</body>

</html>
