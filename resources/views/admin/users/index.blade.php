@extends('layouts.app')
@section('title', 'Manage users')

@section('content')
<h1 class="h4 fw-bold mb-3">Manage users</h1>

<form class="row g-2 mb-3">
    <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or email"></div>
    <div class="col-md-3">
        <select name="role" class="form-select">
            <option value="">All roles</option>
            @foreach(\App\Models\User::ROLES as $role)
                <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-grid"><button class="btn btn-primary">Filter</button></div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>User</th><th>Role</th><th>Activity</th><th>Joined</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}<br><small class="text-muted">{{ $user->email }}</small></td>
                    <td>
                        @if($user->id === auth()->id())
                            <span class="badge text-bg-dark">admin (you)</span>
                        @else
                            <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                @csrf @method('PATCH')
                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach(\App\Models\User::ROLES as $role)
                                        <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                    </td>
                    <td class="small text-muted">
                        @if($user->isEmployer()) {{ $user->job_listings_count }} jobs posted
                        @elseif($user->isSeeker()) {{ $user->applications_count }} applications
                        @else — @endif
                    </td>
                    <td class="small">{{ $user->created_at->format('d M Y') }}</td>
                    <td><span class="badge text-bg-{{ $user->is_blocked ? 'danger' : 'success' }}">{{ $user->is_blocked ? 'Blocked' : 'Active' }}</span></td>
                    <td class="text-end text-nowrap">
                        @unless($user->id === auth()->id())
                            <form method="POST" action="{{ route('admin.users.block', $user) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-warning">{{ $user->is_blocked ? 'Unblock' : 'Block' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Delete this user and all their data?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
