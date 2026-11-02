{{-- One job in a grid. Expects $job --}}
<div class="card job-card h-100 border-0 shadow-sm">
    <div class="card-body d-flex flex-column">
        <div class="d-flex gap-3 mb-3">
            <div class="company-logo">{{ strtoupper(substr($job->company_name, 0, 2)) }}</div>
            <div class="min-w-0">
                <h5 class="card-title mb-0 text-truncate">
                    <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none text-dark stretched-link">{{ $job->title }}</a>
                </h5>
                <small class="text-muted">{{ $job->company_name }}</small>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 mb-3 small">
            <span class="badge text-bg-light border"><i class="bi bi-geo-alt"></i> {{ $job->location }}</span>
            <span class="badge text-bg-primary">{{ $job->job_type }}</span>
            <span class="badge text-bg-light border">{{ $job->category }}</span>
        </div>
        <div class="mt-auto d-flex justify-content-between align-items-center small">
            <span class="fw-semibold text-success">{{ $job->salary_range }}</span>
            <span class="text-muted">{{ $job->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>
