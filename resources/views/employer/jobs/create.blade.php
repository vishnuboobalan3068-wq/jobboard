@extends('layouts.app')
@section('title', 'Post a job')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h1 class="h4 fw-bold mb-1">Post a new job</h1>
        <p class="text-muted small mb-4">Job seekers with matching job alerts will be notified automatically.</p>
        <form method="POST" action="{{ route('employer.jobs.store') }}">
            @csrf
            @include('employer.jobs._form')
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-megaphone"></i> Publish job</button>
                <a href="{{ route('employer.jobs.index') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
