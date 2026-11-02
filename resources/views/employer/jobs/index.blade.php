@extends('layouts.app')
@section('title', 'My jobs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 fw-bold mb-0">My job postings</h1>
    <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Post a job</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="text-center">Applicants</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td><a href="{{ route('jobs.show', $job) }}" class="fw-semibold text-decoration-none">{{ $job->title }}</a></td>
                    <td>{{ $job->location }}</td>
                    <td>{{ $job->job_type }}</td>
                    <td>{{ $job->deadline?->format('d M Y') ?? '—' }}</td>
                    <td><span class="badge text-bg-{{ $job->status === 'open' ? 'success' : 'secondary' }}">{{ ucfirst($job->status) }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('employer.jobs.applications', $job) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-people"></i> {{ $job->applications_count }}
                        </a>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('employer.jobs.destroy', $job) }}" class="d-inline" onsubmit="return confirm('Delete this job and all its applications?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-5">You have not posted any jobs yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $jobs->links() }}</div>
@endsection
