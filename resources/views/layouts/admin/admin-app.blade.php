<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favion.png') }}">

    <title>@yield('title')</title>

    @include('layouts.admin.top')

    @yield('page-css')
</head>

<body>
    <div class="wrapper">
        @include('layouts.admin.header')

        @if(Auth::user()->hasRole("Admin"))
            @include('layouts.admin.sidebar')
        @elseif(Auth::user()->hasRole("Cashier"))
            @include('layouts.admin.cashier-sidebar')
        @else
            @include('layouts.admin.user-sidebar')
        @endif
        @yield('content')
        @include('layouts.admin.footer')
    </div>
    @include('layouts.admin.footer-scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#toggle_filter").click(()=>{
            $("#show-hide-card").css('display') == "none" ? 
            $("#icon_chev").removeClass("fa-chevron-circle-down").addClass("fa-chevron-circle-up") : 
            $("#icon_chev").removeClass("fa-chevron-circle-up").addClass("fa-chevron-circle-down") ;
            $("#show-hide-card").slideToggle();
        })
    </script>
    @yield('page-scripts')
    @include('layouts.admin.flash-message')
</body>

</html>
