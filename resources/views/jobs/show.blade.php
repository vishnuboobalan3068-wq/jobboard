@extends('layouts.app')
@section('title', $job->title)

@section('content')
<a href="{{ route('jobs.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Back to jobs</a>

<div class="row g-4 mt-1">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex gap-3 align-items-start mb-3">
                    <div class="company-logo">{{ strtoupper(substr($job->company_name, 0, 2)) }}</div>
                    <div>
                        <h1 class="h3 fw-bold mb-1">{{ $job->title }}</h1>
                        <div class="text-muted">{{ $job->company_name }} · <i class="bi bi-geo-alt"></i> {{ $job->location }}</div>
                    </div>
                    @unless($job->isOpen())
                        <span class="badge text-bg-danger ms-auto">Closed</span>
                    @endunless
                </div>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge text-bg-primary">{{ $job->job_type }}</span>
                    <span class="badge text-bg-light border">{{ $job->category }}</span>
                    <span class="badge text-bg-success">{{ $job->salary_range }} / year</span>
                </div>

                <h2 class="h5 fw-bold">Job description</h2>
                <p style="white-space: pre-line">{{ $job->description }}</p>

                @if($job->requirements)
                    <h2 class="h5 fw-bold mt-4">Requirements</h2>
                    <ul>
                        @foreach(preg_split('/\r\n|\n/', $job->requirements) as $line)
                            @if(trim($line) !== '')
                                <li>{{ $line }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-5 text-muted fw-normal">Posted</dt><dd class="col-7">{{ $job->created_at->format('d M Y') }}</dd>
                    <dt class="col-5 text-muted fw-normal">Deadline</dt><dd class="col-7">{{ $job->deadline?->format('d M Y') ?? 'Open until filled' }}</dd>
                    <dt class="col-5 text-muted fw-normal">Posted by</dt><dd class="col-7 mb-0">{{ $job->employer->name }}</dd>
                </dl>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @guest
                    <h2 class="h6 fw-bold">Interested in this job?</h2>
                    <p class="small text-muted">Login as a job seeker to apply with your resume.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Login to apply</a>
                @else
                    @if(auth()->user()->isSeeker())
                        @if($alreadyApplied)
                            <div class="alert alert-success mb-0"><i class="bi bi-check-circle"></i> You already applied. <a href="{{ route('seeker.applications.index') }}">Track status</a></div>
                        @elseif(! $job->isOpen())
                            <div class="alert alert-secondary mb-0">This job is no longer accepting applications.</div>
                        @else
                            <h2 class="h6 fw-bold mb-3">Apply for this job</h2>
                            <form method="POST" action="{{ route('seeker.apply', $job) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small">Resume (PDF/DOC, max 2 MB)</label>
                                    <input type="file" name="resume" accept=".pdf,.doc,.docx" class="form-control @error('resume') is-invalid @enderror" {{ auth()->user()->resume_path ? '' : 'required' }}>
                                    @if(auth()->user()->resume_path)
                                        <div class="form-text">Leave empty to use the resume saved on your profile.</div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small">Cover letter (optional)</label>
                                    <textarea name="cover_letter" rows="5" class="form-control" placeholder="Why are you a good fit?">{{ old('cover_letter') }}</textarea>
                                </div>
                                <button class="btn btn-primary w-100"><i class="bi bi-send"></i> Submit application</button>
                            </form>
                        @endif
                    @elseif(auth()->id() === $job->user_id)
                        <a href="{{ route('employer.jobs.applications', $job) }}" class="btn btn-primary w-100 mb-2">View applicants</a>
                        <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-outline-secondary w-100">Edit job</a>
                    @else
                        <p class="small text-muted mb-0">Only job seeker accounts can apply.</p>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
