{{-- Shared by create and edit. Expects $job --}}
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Job title *</label>
        <input type="text" name="title" value="{{ old('title', $job->title) }}" class="form-control @error('title') is-invalid @enderror" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Company name *</label>
        <input type="text" name="company_name" value="{{ old('company_name', $job->company_name) }}" class="form-control @error('company_name') is-invalid @enderror" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Location *</label>
        <input type="text" name="location" value="{{ old('location', $job->location) }}" class="form-control @error('location') is-invalid @enderror" placeholder="Chennai / Remote" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Category *</label>
        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
            <option value="">Choose...</option>
            @foreach(\App\Models\JobListing::CATEGORIES as $c)
                <option value="{{ $c }}" @selected(old('category', $job->category) === $c)>{{ $c }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Job type *</label>
        <select name="job_type" class="form-select @error('job_type') is-invalid @enderror" required>
            <option value="">Choose...</option>
            @foreach(\App\Models\JobListing::TYPES as $t)
                <option value="{{ $t }}" @selected(old('job_type', $job->job_type) === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Min salary (₹ / year)</label>
        <input type="number" min="0" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="form-control @error('salary_min') is-invalid @enderror">
    </div>
    <div class="col-md-4">
        <label class="form-label">Max salary (₹ / year)</label>
        <input type="number" min="0" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="form-control @error('salary_max') is-invalid @enderror">
    </div>
    <div class="col-md-4">
        <label class="form-label">Application deadline</label>
        <input type="date" name="deadline" value="{{ old('deadline', $job->deadline?->format('Y-m-d')) }}" class="form-control @error('deadline') is-invalid @enderror">
    </div>

    <div class="col-12">
        <label class="form-label">Description *</label>
        <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $job->description) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Requirements <small class="text-muted">(one per line)</small></label>
        <textarea name="requirements" rows="4" class="form-control">{{ old('requirements', $job->requirements) }}</textarea>
    </div>

    @if($job->exists)
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="open" @selected(old('status', $job->status) === 'open')>Open</option>
                <option value="closed" @selected(old('status', $job->status) === 'closed')>Closed</option>
            </select>
        </div>
    @endif
</div>
