@extends('layouts.app')
@section('title', 'Job alerts')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h5 fw-bold"><i class="bi bi-bell"></i> Create a job alert</h1>
                <p class="small text-muted">When an employer posts a job that matches, you get a notification (and an email).</p>
                <form method="POST" action="{{ route('seeker.alerts.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Keyword</label>
                        <input type="text" name="keyword" value="{{ old('keyword') }}" class="form-control" placeholder="e.g. PHP, Designer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" class="form-control" placeholder="e.g. Chennai">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Category</label>
                        <select name="category" class="form-select">
                            <option value="">Any</option>
                            @foreach(\App\Models\JobListing::CATEGORIES as $c)
                                <option value="{{ $c }}" @selected(old('category') === $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Job type</label>
                        <select name="job_type" class="form-select">
                            <option value="">Any</option>
                            @foreach(\App\Models\JobListing::TYPES as $t)
                                <option value="{{ $t }}" @selected(old('job_type') === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary w-100">Save alert</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <h2 class="h5 fw-bold mb-3">My alerts</h2>
        @forelse($alerts as $alert)
            <div class="card border-0 shadow-sm mb-2">
                <div class="card-body d-flex justify-content-between align-items-center gap-2">
                    <div>
                        <div class="fw-semibold">{{ $alert->summary }}</div>
                        <small class="text-muted">Created {{ $alert->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <form method="POST" action="{{ route('seeker.alerts.toggle', $alert) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm {{ $alert->is_active ? 'btn-success' : 'btn-outline-secondary' }}">
                                {{ $alert->is_active ? 'Active' : 'Paused' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('seeker.alerts.destroy', $alert) }}" onsubmit="return confirm('Delete this alert?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5 bg-white rounded-3 shadow-sm">No alerts yet. Create one on the left.</div>
        @endforelse
    </div>
</div>
@endsection
