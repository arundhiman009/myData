<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home.page') }}" class="p-0">
        <div class="dashboard-logo">
            <img src="{{ asset('assets/images/dashboard-logo.png') }}" alt="Logo" class="img-fluid" style="opacity: .8">
        </div>
    </a>

    <div class="sidebar px-0">
        <div class="user-panel mt-3 pb-1 d-flex">
            <div class="image my-auto">
                <div class="avatar-sm">
                    <span class="avatar-title bg-soft-secondary font-12 rounded-circle receiever-name">
                        {!! getFirstLetterString(Auth::user()->username) !!}
                    </span>
                </div>
            </div>
            <div class="info">
                <a href="javascript: void(0)" class="d-block">{{ ucfirst(Auth::user()->username) }}</a>
                <a href="javascript: void(0)"><small class="text-white">{{Auth::user()->getRoleNames()->first()}}</small></a>
            </div>
        </div>
        <nav >
            <ul class="nav nav-pills nav-sidebar flex-column dashboard-nav" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('*online') || request()->is('*load-money-request') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-money-bill-alt"></i>
                      <p>
                        Money
                        <i class="fas fa-angle-left mt-1 right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('load.money',['type'=>'online']) }}" class="nav-link  {{ request()->is('*online') ? 'cu-ac' : '' }}">
                                <i class="nav-icon fas fa-plus-square"></i>
                                <p> Load Money </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.loadmoney.request') }}" class="nav-link {{ request()->is('*load-money-request') ? 'cu-ac' : '' }}">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p> Wallet </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('cashout.index') }}" class="nav-link {{ request()->is('*cashout-request') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Cashout Requests </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('*chat') ? 'menu-open' : '' }}">
                    <a href="{{route('chat.index')}}" class="nav-link {{ request()->is('*chat') ? 'active' : ''}}">
                       <i class="nav-icon fas fa-comments"></i>
                        <p>Messages</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('social-share') ? 'menu-open' : '' }}">
                    <a href="{{ route('social.share') }}" class="nav-link {{ request()->is('social-share') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-share-alt-square"></i>
                        <p> Social Share </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
