<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <div id="sidebarOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,.4); z-index:1025;"></div>

        <aside class="sidebar" id="appSidebar">
            <div class="sidebar-brand">
                <div class="logo-badge">L</div>
                <div>
                    LMS SMKN 9 Garut
                    <div style="font-size:.65rem; font-weight:400; color:#94a3b8;">Learning Management System</div>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                @include('layouts.partials.sidebar-admin')
            @elseif(auth()->user()->isTeacher())
                @include('layouts.partials.sidebar-guru')
            @else
                @include('layouts.partials.sidebar-siswa')
            @endif
        </aside>

        <div class="main-content">
            <header class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggle-btn" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                    <h5 class="mb-0 d-none d-md-block">@yield('page-title', 'Dashboard')</h5>
                </div>

                <div class="d-flex align-items-center gap-3">
                    @include('layouts.partials.notifications-dropdown')

                    <div class="dropdown">
                        <button class="btn d-flex align-items-center gap-2 border-0" type="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}" class="avatar-circle" alt="avatar">
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down small"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? '') }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa-solid fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.password') }}"><i class="fa-solid fa-key me-2"></i>Ganti Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="page-content">
                @include('layouts.partials.flash-messages')
                @yield('content')
            </main>

            <footer class="text-center text-muted small py-3">
                &copy; {{ date('Y') }} SMKN 9 Garut &mdash; Learning Management System
            </footer>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
