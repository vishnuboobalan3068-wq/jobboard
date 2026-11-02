@extends('layouts.app')
@section('title', 'Create account')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 fw-bold mb-1">Create your account</h1>
                <p class="text-muted mb-4">Are you hiring or looking for a job?</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row g-2 mb-3">
                        @foreach(['seeker' => ['person-badge', 'I want a job', 'Search & apply for jobs'], 'employer' => ['building', 'I am hiring', 'Post jobs & review applicants']] as $value => [$icon, $label, $hint])
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="role" id="role-{{ $value }}" value="{{ $value }}"
                                       {{ old('role', 'seeker') === $value ? 'checked' : '' }} onchange="toggleCompany()">
                                <label class="btn btn-outline-primary w-100 py-3" for="role-{{ $value }}">
                                    <i class="bi bi-{{ $icon }} fs-3 d-block"></i>
                                    <span class="fw-semibold">{{ $label }}</span>
                                    <small class="d-block opacity-75">{{ $hint }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    </div>
                    <div class="mb-3" id="companyField">
                        <label class="form-label">Company name</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control @error('company_name') is-invalid @enderror">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100">Create account</button>
                </form>

                <p class="text-center mt-4 mb-0 small">Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCompany() {
        const isEmployer = document.getElementById('role-employer').checked;
        document.getElementById('companyField').style.display = isEmployer ? '' : 'none';
    }
    toggleCompany();
</script>
@endsection
