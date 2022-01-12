<!-- Header Start-->
<header class="top-header">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col-4 col-sm-2 pl-0">
                <div class="logo">
                    <a href="{{ route('home.page') }}">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="{{ Config::get('app.name') }} logo" class="img-fluid">
                    </a>
                </div>
            </div>
            <div class="col-8 col-md-6 pr-0 text-right">
                @guest
                    <a href="{{ route('login') }}" class="btn custom-btn cusn-bg-grident"><i class="las la-user"></i> Sign in </a>
                    <a href="{{ route('register') }}" class="btn custom-btn"><i class="las la-plus"></i> Sign up</a>
                @else
                @if(Auth::user()->hasRole("Admin"))
                    <a href="{{ route('dashboard') }}" class="btn custom-btn cusn-bg-grident"><i class="las la-tachometer-alt"></i> Dashboard</a>
                    @elseif(Auth::user()->hasRole("Cashier"))
                    <a href="{{ route('dashboard') }}" class="btn custom-btn cusn-bg-grident"><i class="las la-tachometer-alt"></i> Dashboard</a>
                    @else
                    <a href="{{ route('dashboard') }}" class="btn custom-btn cusn-bg-grident"><i class="las la-tachometer-alt"></i> Dashboard</a>
                    @endif
                    <a href="{{ route('logout') }}" class="btn custom-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" > <i class="las la-sign-out-alt"></i> Logout
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>
<!-- Header  End-->
