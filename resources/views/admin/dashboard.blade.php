@extends('layouts.app')
@section('title', 'Admin dashboard')

@section('content')
<h1 class="h4 fw-bold mb-3">Admin dashboard</h1>

<div class="row g-3 mb-4">
    @foreach([
        ['Employers', $stats['employers'], 'building', 'primary'],
        ['Job seekers', $stats['seekers'], 'people', 'info'],
        ['Open jobs', $stats['openJobs'], 'briefcase', 'success'],
        ['Closed jobs', $stats['closedJobs'], 'archive', 'secondary'],
        ['Applications', $stats['applications'], 'file-earmark-text', 'warning'],
        ['Blocked users', $stats['blocked'], 'slash-circle', 'danger'],
    ] as [$label, $value, $icon, $color])
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="icon bg-{{ $color }}-subtle text-{{ $color }} mb-2"><i class="bi bi-{{ $icon }}"></i></div>
                    <div class="fs-3 fw-bold">{{ $value }}</div>
                    <div class="small text-muted">{{ $label }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between">
                <span class="fw-bold">Recent job listings</span>
                <a href="{{ route('admin.jobs.index') }}" class="small">Manage all</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <tbody>
                    @forelse($recentJobs as $job)
                        <tr>
                            <td><a href="{{ route('jobs.show', $job) }}" class="text-decoration-none fw-semibold">{{ $job->title }}</a><br><small class="text-muted">{{ $job->company_name }}</small></td>
                            <td class="small">{{ $job->employer?->name }}</td>
                            <td><span class="badge text-bg-{{ $job->status === 'open' ? 'success' : 'secondary' }}">{{ ucfirst($job->status) }}</span></td>
                            <td class="small text-muted">{{ $job->applications_count }} applicants</td>
                        </tr>
                    @empty
                        <tr><td class="text-muted text-center py-4">No jobs yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Applications by status</div>
            <div class="card-body">
                @php $totalApps = max(1, $stats['applications']); @endphp
                @foreach(\App\Models\Application::STATUSES as $status)
                    @php $count = $applicationsByStatus[$status] ?? 0; @endphp
                    <div class="d-flex justify-content-between small mb-1"><span>{{ ucfirst($status) }}</span><span>{{ $count }}</span></div>
                    <div class="progress mb-3" style="height: 8px">
                        <div class="progress-bar" style="width: {{ round($count / $totalApps * 100) }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <span class="fw-bold">New users</span>
                <a href="{{ route('admin.users.index') }}" class="small">Manage all</a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($recentUsers as $u)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $u->name }}<br><small class="text-muted">{{ $u->email }}</small></span>
                        <span class="badge text-bg-light border align-self-center text-uppercase">{{ $u->role }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
