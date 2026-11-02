@extends('layouts.app')
@section('title', 'Applicants')

@section('content')
<a href="{{ route('employer.jobs.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> My jobs</a>
<h1 class="h4 fw-bold mt-2 mb-3">Applicants for “{{ $job->title }}” <span class="badge text-bg-primary">{{ $applications->count() }}</span></h1>

@forelse($applications as $application)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-start">
                <div>
                    <h2 class="h6 fw-bold mb-1">{{ $application->user->name }}</h2>
                    <div class="small text-muted">
                        <i class="bi bi-envelope"></i> {{ $application->user->email }}
                        @if($application->user->phone) · <i class="bi bi-telephone"></i> {{ $application->user->phone }} @endif
                        · Applied {{ $application->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('applications.resume', $application) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Resume</a>
                    <form method="POST" action="{{ route('employer.applications.update', $application) }}" class="d-flex gap-2">
                        @csrf @method('PATCH')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach(\App\Models\Application::STATUSES as $status)
                                <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="badge text-bg-{{ $application->status_color }}">{{ ucfirst($application->status) }}</span>
                </div>
            </div>
            @if($application->cover_letter)
                <details class="mt-2 small">
                    <summary class="text-primary">Cover letter</summary>
                    <p class="mt-2 mb-0" style="white-space: pre-line">{{ $application->cover_letter }}</p>
                </details>
            @endif
        </div>
    </div>
@empty
    <div class="text-center text-muted py-5 bg-white rounded-3 shadow-sm">No applications yet.</div>
@endforelse
@endsection
