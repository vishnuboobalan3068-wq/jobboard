@extends('layouts.app')
@section('title', 'My applications')

@section('content')
<h1 class="h4 fw-bold mb-3">My applications</h1>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Job</th>
                    <th>Company</th>
                    <th>Applied on</th>
                    <th>Status</th>
                    <th class="text-end">Resume</th>
                </tr>
            </thead>
            <tbody>
            @forelse($applications as $application)
                <tr>
                    <td>
                        @if($application->jobListing)
                            <a href="{{ route('jobs.show', $application->jobListing) }}" class="fw-semibold text-decoration-none">{{ $application->jobListing->title }}</a>
                        @endif
                    </td>
                    <td>{{ $application->jobListing?->company_name }}</td>
                    <td>{{ $application->created_at->format('d M Y') }}</td>
                    <td><span class="badge text-bg-{{ $application->status_color }}">{{ ucfirst($application->status) }}</span></td>
                    <td class="text-end"><a href="{{ route('applications.resume', $application) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        You have not applied for any jobs yet. <a href="{{ route('jobs.index') }}">Browse jobs</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $applications->links() }}</div>
@endsection
