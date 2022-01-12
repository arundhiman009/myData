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
                <a href="javascript: void(0)" class="d-block">{{ Auth::user()->username }}</a>
                <a href="javascript: void(0)"><small class="text-white">{{Auth::user()->getRoleNames()->first()}}</small></a>
            </div>
        </div>
        <nav >
            <ul class="nav nav-pills nav-sidebar flex-column dashboard-nav" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('*dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('*cashout-location') || request()->is('*slot-creation') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-map-marker"></i>
                        <p>Manage Locations<i class="fas fa-angle-left mt-1 right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item {{ request()->is('*cashout-location') ? 'menu-open' : '' }}">
                            <a href="{{ route('admin.cashout') }}" class="nav-link {{ request()->is('*cashout-location') ? 'cu-ac' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Cashout Location </p>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('*slot-creation') ? 'menu-open' : '' }}">
                            <a href="{{ route('admin.slot-creation') }}" class="nav-link {{ request()->is('*slot-creation') ? 'cu-ac' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Time Slot Creation</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.promo-creation') }}" class="nav-link {{ request()->is('*promo-creation') ? 'active' : '' }}">
                        <i class="fa fa-tag nav-icon"></i>
                        <p> Promo Code</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('*cashout-request') || request()->is('*load-money-request') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-tasks"></i>
                      <p>Requests<i class="fas fa-angle-left mt-1 right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('cashout.index') }}" class="nav-link {{ request()->is('*cashout-request') ? 'cu-ac' : '' }}">
                                <i class="fas fa-money-bill-alt"></i>
                                <p> Cashout Requests </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.loadmoney.request') }}" class="nav-link {{ request()->is('*load-money-request') ? 'cu-ac' : '' }}">
                                <i class="fas fa-money-bill-alt"></i>
                                <p> Load Money Requests </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('*load-money-report') ? 'menu-open' : '' }}">
                    <a href="{{route('admin.loadmoney.report')}}" class="nav-link {{ request()->is('*load-money-report') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Load Money Report</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('*cashier') || request()->is('*users') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-user-friends"></i>
                      <p>User Management <i class="fas fa-angle-left mt-1 right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.cashier') }}" class="nav-link {{ request()->is('*cashier') ? 'cu-ac' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Hosts </p>
                            </a>
                        </li>  

                        <li class="nav-item">
                            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->is('*users') ? 'cu-ac' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customers</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('chat.index')}}" class="nav-link {{ request()->is('*chat') ? 'active' : ''}}">
                      <i class="nav-icon fas fa-comments"></i>
                        <p>Messages</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('*settings') || request()->is('*games') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                      <p> General Settings <i class="fas fa-angle-left mt-1 right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item {{ request()->is('*roles-list') ? 'menu-open' : '' }}">
                            <a href="{{route('admin.settings')}}" class="nav-link {{ request()->is('*settings') ? 'cu-ac' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Settings</p>
                            </a>
                        </li>  

                        <li class="nav-item {{ request()->is('*Permission-list') ? 'menu-open' : '' }}">
                            <a href="{{ route('admin.games') }}" class="nav-link {{ request()->is('*games') ? 'cu-ac' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Game</p>
                            </a>
                        </li>
                    </ul>
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