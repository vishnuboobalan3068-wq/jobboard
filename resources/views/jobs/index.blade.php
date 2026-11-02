@extends('layouts.app')
@section('title', 'Browse jobs')

@section('content')
<div class="row g-4">
    {{-- Filters --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-funnel"></i> Filter jobs</h2>
                <form action="{{ route('jobs.index') }}">
                    <div class="mb-3">
                        <label class="form-label small">Keyword</label>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="e.g. Laravel">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Location</label>
                        <input type="text" name="location" value="{{ $filters['location'] ?? '' }}" class="form-control" placeholder="e.g. Chennai">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All categories</option>
                            @foreach(\App\Models\JobListing::CATEGORIES as $c)
                                <option value="{{ $c }}" @selected(($filters['category'] ?? '') === $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Job type</label>
                        <select name="type" class="form-select">
                            <option value="">Any type</option>
                            @foreach(\App\Models\JobListing::TYPES as $t)
                                <option value="{{ $t }}" @selected(($filters['type'] ?? '') === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">Search</button>
                        <a href="{{ route('jobs.index') }}" class="btn btn-link btn-sm">Clear filters</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="col-lg-9">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <h1 class="h4 fw-bold mb-0">{{ $jobs->total() }} job{{ $jobs->total() === 1 ? '' : 's' }} found</h1>
            @auth
                @if(auth()->user()->isSeeker() && array_filter($filters))
                    <form method="POST" action="{{ route('seeker.alerts.store') }}">
                        @csrf
                        <input type="hidden" name="keyword" value="{{ $filters['q'] ?? '' }}">
                        <input type="hidden" name="location" value="{{ $filters['location'] ?? '' }}">
                        <input type="hidden" name="category" value="{{ $filters['category'] ?? '' }}">
                        <input type="hidden" name="job_type" value="{{ $filters['type'] ?? '' }}">
                        <button class="btn btn-outline-primary btn-sm"><i class="bi bi-bell"></i> Alert me for this search</button>
                    </form>
                @endif
            @endauth
        </div>

        <div class="row g-3">
            @forelse($jobs as $job)
                <div class="col-md-6 col-xl-4">@include('jobs._card')</div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5 bg-white rounded-3 shadow-sm">
                        <i class="bi bi-search fs-1 d-block mb-2"></i>
                        No jobs match your search. Try different filters.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $jobs->links() }}</div>
    </div>
</div>
@endsection
