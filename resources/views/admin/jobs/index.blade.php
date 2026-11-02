@extends('layouts.app')
@section('title', 'Manage listings')

@section('content')
<h1 class="h4 fw-bold mb-3">Manage job listings</h1>

<form class="row g-2 mb-3">
    <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search title, company or description"></div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">Any status</option>
            <option value="open" @selected(request('status') === 'open')>Open</option>
            <option value="closed" @selected(request('status') === 'closed')>Closed</option>
        </select>
    </div>
    <div class="col-md-3 d-grid"><button class="btn btn-primary">Filter</button></div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Job</th><th>Employer</th><th>Posted</th><th>Applicants</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td><a href="{{ route('jobs.show', $job) }}" class="fw-semibold text-decoration-none">{{ $job->title }}</a><br><small class="text-muted">{{ $job->company_name }} · {{ $job->location }}</small></td>
                    <td class="small">{{ $job->employer?->name }}</td>
                    <td class="small">{{ $job->created_at->format('d M Y') }}</td>
                    <td>{{ $job->applications_count }}</td>
                    <td><span class="badge text-bg-{{ $job->status === 'open' ? 'success' : 'secondary' }}">{{ ucfirst($job->status) }}</span></td>
                    <td class="text-end text-nowrap">
                        <form method="POST" action="{{ route('admin.jobs.status', $job) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary">{{ $job->status === 'open' ? 'Close' : 'Reopen' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" class="d-inline" onsubmit="return confirm('Delete this listing?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No listings found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $jobs->links() }}</div>
@endsection
