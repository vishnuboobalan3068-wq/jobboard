@extends('layouts.app')

@section('hero')
<section class="hero py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold">Find the job that fits your life</h1>
                <p class="lead opacity-75 mb-4">{{ $stats['jobs'] }} open positions from {{ $stats['companies'] }} companies. Search, apply with your resume, and get alerts for new jobs.</p>

                <form action="{{ route('jobs.index') }}" class="bg-white rounded-3 p-2 shadow">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" name="q" class="form-control form-control-lg border-0" placeholder="Job title, skill or company">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="location" class="form-control form-control-lg border-0" placeholder="City or Remote">
                        </div>
                        <div class="col-md-3 d-grid">
                            <button class="btn btn-primary btn-lg"><i class="bi bi-search"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-end mb-3 mt-2">
    <h2 class="h4 fw-bold mb-0">Latest jobs</h2>
    <a href="{{ route('jobs.index') }}" class="small">View all <i class="bi bi-arrow-right"></i></a>
</div>

<div class="row g-3 mb-5">
    @forelse($latestJobs as $job)
        <div class="col-md-6 col-lg-4">@include('jobs._card')</div>
    @empty
        <p class="text-muted">No jobs posted yet.</p>
    @endforelse
</div>

<h2 class="h4 fw-bold mb-3">Browse by category</h2>
<div class="row g-3 mb-5">
    @foreach(\App\Models\JobListing::CATEGORIES as $category)
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('jobs.index', ['category' => $category]) }}" class="card border-0 shadow-sm text-decoration-none job-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span class="text-dark fw-semibold">{{ $category }}</span>
                    <span class="badge rounded-pill text-bg-primary">{{ $categoryCounts[$category] ?? 0 }}</span>
                </div>
            </a>
        </div>
    @endforeach
</div>

@guest
<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 bg-primary-subtle">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold"><i class="bi bi-person-badge"></i> Looking for a job?</h3>
                <p class="mb-3">Create a profile, upload your resume once and apply in one click. Set job alerts so you never miss a match.</p>
                <a href="{{ route('register') }}" class="btn btn-primary">Join as job seeker</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 bg-success-subtle">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold"><i class="bi bi-building"></i> Hiring?</h3>
                <p class="mb-3">Post jobs for free, review resumes and shortlist candidates from one dashboard.</p>
                <a href="{{ route('register') }}" class="btn btn-success">Post a job</a>
            </div>
        </div>
    </div>
</div>
@endguest
@endsection
