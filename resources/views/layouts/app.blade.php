<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Find your next job') · JobBoard</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Teal theme: re-point Bootstrap's primary color so every page picks it up */
        :root {
            --jb-dark: #04342C; --jb-accent: #0F6E56; --jb-accent-hover: #085041; --jb-light: #E1F5EE; --jb-bg: #F1F5F4;
            --bs-primary: #0F6E56; --bs-primary-rgb: 15, 110, 86;
            --bs-primary-bg-subtle: #E1F5EE; --bs-primary-border-subtle: #9FE1CB; --bs-primary-text-emphasis: #085041;
            --bs-link-color: #0F6E56; --bs-link-color-rgb: 15, 110, 86;
            --bs-link-hover-color: #085041; --bs-link-hover-color-rgb: 8, 80, 65;
        }
        .btn-primary { --bs-btn-bg: #0F6E56; --bs-btn-border-color: #0F6E56; --bs-btn-hover-bg: #085041; --bs-btn-hover-border-color: #085041;
                       --bs-btn-active-bg: #04342C; --bs-btn-active-border-color: #04342C; --bs-btn-disabled-bg: #0F6E56; --bs-btn-disabled-border-color: #0F6E56; }
        .btn-outline-primary { --bs-btn-color: #0F6E56; --bs-btn-border-color: #0F6E56; --bs-btn-hover-bg: #0F6E56; --bs-btn-hover-border-color: #0F6E56;
                               --bs-btn-active-bg: #085041; --bs-btn-active-border-color: #085041; }
        .form-control:focus, .form-select:focus { border-color: #5DCAA5; box-shadow: 0 0 0 .25rem rgba(15, 110, 86, .2); }
        .form-check-input:checked { background-color: #0F6E56; border-color: #0F6E56; }
        .form-check-input:focus { border-color: #5DCAA5; box-shadow: 0 0 0 .25rem rgba(15, 110, 86, .2); }

        body { background: var(--jb-bg); }

        /* Sidebar: an off-canvas drawer on phones, a fixed column from the lg breakpoint */
        .jb-sidebar { --bs-offcanvas-width: 260px; --bs-offcanvas-bg: var(--jb-dark); --bs-offcanvas-color: #fff;
                      background-color: var(--jb-dark) !important; color: #fff; }
        @media (min-width: 992px) {
            .jb-sidebar { width: 250px; flex-shrink: 0; position: sticky; top: 0; height: 100vh; }
            .jb-sidebar .offcanvas-body { height: 100%; overflow-y: auto; }
        }
        .jb-nav .nav-link { color: #B9DDD2; border-radius: .5rem; padding: .55rem .75rem; display: flex; align-items: center; gap: .65rem; }
        .jb-nav .nav-link:hover { background: rgba(255, 255, 255, .08); color: #fff; }
        .jb-nav .nav-link.active { background: var(--jb-accent); color: #fff; }
        .jb-nav-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: #5DCAA5; margin: 1.1rem .75rem .35rem; }
        .jb-avatar { width: 36px; height: 36px; border-radius: 50%; display: grid; place-items: center; font-weight: 700;
                     background: var(--jb-light); color: var(--jb-accent-hover); flex-shrink: 0; }
        .jb-topbar { background: #fff; border-bottom: 1px solid #dfe7e4; }

        .hero { background: linear-gradient(135deg, var(--jb-dark) 0%, var(--jb-accent) 100%); color: #fff; border-radius: 1rem; margin-bottom: 1.5rem; }
        .job-card { transition: transform .15s ease, box-shadow .15s ease; }
        .job-card:hover { transform: translateY(-3px); box-shadow: 0 .75rem 1.5rem rgba(15, 110, 86, .14) !important; }
        .company-logo { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center;
                        font-weight: 700; background: var(--jb-light); color: var(--jb-accent); flex-shrink: 0; }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center; font-size: 1.4rem; }
        .min-w-0 { min-width: 0; }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $unread = $user ? $user->unreadNotifications()->count() : 0;
    $navLink = fn (bool $active) => 'nav-link'.($active ? ' active' : '');
@endphp

<div class="d-flex min-vh-100">
    <aside class="offcanvas-lg offcanvas-start jb-sidebar" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header border-bottom border-light border-opacity-10">
            <a class="d-flex align-items-center gap-2 text-white text-decoration-none fw-bold" href="{{ route('home') }}" id="sidebarLabel">
                <img src="{{ asset('logo.svg') }}" alt="" width="34" height="34" class="rounded-3"> JobBoard
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close menu"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column p-3">
            <a class="d-none d-lg-flex align-items-center gap-2 text-white text-decoration-none fw-bold fs-5 px-2 mb-2" href="{{ route('home') }}">
                <img src="{{ asset('logo.svg') }}" alt="" width="36" height="36" class="rounded-3"> JobBoard
            </a>

            <nav class="jb-nav flex-grow-1" aria-label="Main menu">
                <div class="jb-nav-label">Menu</div>
                <ul class="nav flex-column gap-1">
                    <li><a class="{{ $navLink(request()->routeIs('home')) }}" href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                    <li><a class="{{ $navLink(request()->routeIs('jobs.*')) }}" href="{{ route('jobs.index') }}"><i class="bi bi-search"></i> Browse jobs</a></li>
                </ul>

                @if($user?->isEmployer())
                    <div class="jb-nav-label">Hiring</div>
                    <ul class="nav flex-column gap-1">
                        <li><a class="{{ $navLink(request()->routeIs('employer.jobs.index', 'employer.jobs.edit', 'employer.jobs.applications')) }}" href="{{ route('employer.jobs.index') }}"><i class="bi bi-briefcase"></i> My jobs</a></li>
                        <li><a class="{{ $navLink(request()->routeIs('employer.jobs.create')) }}" href="{{ route('employer.jobs.create') }}"><i class="bi bi-plus-circle"></i> Post a job</a></li>
                    </ul>
                @elseif($user?->isSeeker())
                    <div class="jb-nav-label">My job search</div>
                    <ul class="nav flex-column gap-1">
                        <li><a class="{{ $navLink(request()->routeIs('seeker.applications.*')) }}" href="{{ route('seeker.applications.index') }}"><i class="bi bi-file-earmark-text"></i> My applications</a></li>
                        <li><a class="{{ $navLink(request()->routeIs('seeker.alerts.*')) }}" href="{{ route('seeker.alerts.index') }}"><i class="bi bi-bell"></i> Job alerts</a></li>
                    </ul>
                @elseif($user?->isAdmin())
                    <div class="jb-nav-label">Admin</div>
                    <ul class="nav flex-column gap-1">
                        <li><a class="{{ $navLink(request()->routeIs('admin.dashboard')) }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                        <li><a class="{{ $navLink(request()->routeIs('admin.users.*')) }}" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Users</a></li>
                        <li><a class="{{ $navLink(request()->routeIs('admin.jobs.*')) }}" href="{{ route('admin.jobs.index') }}"><i class="bi bi-list-ul"></i> Listings</a></li>
                    </ul>
                @endif

                @auth
                    <div class="jb-nav-label">Account</div>
                    <ul class="nav flex-column gap-1">
                        <li>
                            <a class="{{ $navLink(request()->routeIs('notifications.*')) }}" href="{{ route('notifications.index') }}">
                                <i class="bi bi-inbox"></i> Notifications
                                @if($unread)<span class="badge rounded-pill bg-danger ms-auto">{{ $unread }}</span>@endif
                            </a>
                        </li>
                        <li><a class="{{ $navLink(request()->routeIs('profile.*')) }}" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear"></i> Profile</a></li>
                    </ul>
                @endauth
            </nav>

            <div class="mt-4 pt-3 border-top border-light border-opacity-10">
                @auth
                    <div class="d-flex align-items-center gap-2 mb-3 px-1">
                        <div class="jb-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="min-w-0">
                            <div class="fw-semibold text-truncate">{{ $user->name }}</div>
                            <small class="text-uppercase" style="color:#5DCAA5;font-size:.7rem">{{ $user->role }}</small>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm w-100 mb-2 fw-semibold" style="color:var(--jb-dark)">Sign up</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm w-100">Login</a>
                @endauth
            </div>
        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column min-w-0">
        <header class="jb-topbar sticky-top px-3 px-lg-4 py-2 d-flex align-items-center gap-2">
            <button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <a class="d-lg-none" href="{{ route('home') }}"><img src="{{ asset('logo.svg') }}" alt="JobBoard home" width="32" height="32" class="rounded-3"></a>

            <form action="{{ route('jobs.index') }}" class="flex-grow-1 mx-1" style="max-width: 520px" role="search">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="search" name="q" value="{{ request()->routeIs('jobs.index') ? request('q') : '' }}" class="form-control border-start-0"
                           placeholder="Search jobs, skills or companies" aria-label="Search jobs">
                </div>
            </form>

            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a class="btn btn-light position-relative" href="{{ route('notifications.index') }}" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        @if($unread)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread }}</span>
                        @endif
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-light d-flex align-items-center gap-2 py-1" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                            <span class="jb-avatar" style="width:30px;height:30px;font-size:.85rem">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            <span class="d-none d-md-inline small fw-semibold">{{ $user->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="btn btn-link text-decoration-none d-none d-sm-inline-block" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary" href="{{ route('register') }}">Sign up</a>
                @endauth
            </div>
        </header>

        <main class="flex-grow-1 p-3 p-lg-4">
            <div class="mx-auto" style="max-width: 1200px">
                @yield('hero')
                @include('partials.flash')
                @yield('content')
            </div>
        </main>

        <footer class="px-3 px-lg-4 py-3 border-top bg-white">
            <div class="mx-auto d-flex flex-wrap gap-2 justify-content-between small text-muted" style="max-width: 1200px">
                <span>&copy; {{ date('Y') }} JobBoard · Built with Laravel &amp; Bootstrap</span>
                <span>Second-year Web Development Project</span>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
