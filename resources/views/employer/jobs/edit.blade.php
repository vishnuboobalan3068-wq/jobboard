@extends('layouts.app')
@section('title', 'Edit job')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h1 class="h4 fw-bold mb-4">Edit: {{ $job->title }}</h1>
        <form method="POST" action="{{ route('employer.jobs.update', $job) }}">
            @csrf @method('PUT')
            @include('employer.jobs._form')
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary">Save changes</button>
                <a href="{{ route('employer.jobs.index') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
