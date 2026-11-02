<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Find your next job') · JobBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; }
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%); color: #fff; }
        .job-card { transition: transform .15s ease, box-shadow .15s ease; }
        .job-card:hover { transform: translateY(-3px); box-shadow: 0 .75rem 1.5rem rgba(13,110,253,.12) !important; }
        .company-logo { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center;
                        font-weight: 700; background: #e7f0ff; color: #0d6efd; flex-shrink: 0; }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center; font-size: 1.4rem; }
        .navbar-brand i { color: #6ea8fe; }
        .min-w-0 { min-width: 0; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="bi bi-briefcase-fill"></i> JobBoard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Browse Jobs</a></li>
                @auth
                    @if(auth()->user()->isEmployer())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('employer.jobs.index') ? 'active' : '' }}" href="{{ route('employer.jobs.index') }}">My Jobs</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}" href="{{ route('employer.jobs.create') }}">Post a Job</a></li>
                    @elseif(auth()->user()->isSeeker())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('seeker.applications.*') ? 'active' : '' }}" href="{{ route('seeker.applications.index') }}">My Applications</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('seeker.alerts.*') ? 'active' : '' }}" href="{{ route('seeker.alerts.index') }}">Job Alerts</a></li>
                    @elseif(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">Listings</a></li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                @auth
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('notifications.index') }}" title="Notifications">
                            <i class="bi bi-bell fs-5"></i>
                            @if($unread)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            <span class="badge bg-secondary text-uppercase ms-1" style="font-size:.65rem">{{ auth()->user()->role }}</span>
                        </a>
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
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm" href="{{ route('register') }}">Sign up</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1">
    @yield('hero')
    <div class="container py-4">
        @include('partials.flash')
        @yield('content')
    </div>
</main>

<footer class="bg-dark text-white-50 py-3 mt-auto">
    <div class="container d-flex flex-wrap justify-content-between small">
        <span>&copy; {{ date('Y') }} JobBoard · Built with Laravel &amp; Bootstrap</span>
        <span>Second-year Web Development Project</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
