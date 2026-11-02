@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 fw-bold mb-4">My profile</h1>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        </div>
                        @if($user->isEmployer())
                            <div class="col-md-6">
                                <label class="form-label">Company name</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="form-control" required>
                            </div>
                        @endif

                        @if($user->isSeeker())
                            <div class="col-12">
                                <label class="form-label">Default resume (PDF/DOC, max 2 MB)</label>
                                <input type="file" name="resume" accept=".pdf,.doc,.docx" class="form-control">
                                @if($user->resume_path)
                                    <div class="form-text"><i class="bi bi-file-earmark-check text-success"></i> Resume saved. <a href="{{ route('profile.resume') }}">Download current</a></div>
                                @else
                                    <div class="form-text">Save a resume here so you can apply to jobs in one click.</div>
                                @endif
                            </div>
                        @endif

                        <div class="col-12"><hr><h2 class="h6 fw-bold mb-0">Change password <small class="text-muted fw-normal">(optional)</small></h2></div>
                        <div class="col-md-4">
                            <label class="form-label small">Current password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">New password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Confirm new password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-primary mt-4">Save profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
