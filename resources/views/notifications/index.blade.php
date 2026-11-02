@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 fw-bold mb-0">Notifications</h1>
    <form method="POST" action="{{ route('notifications.readAll') }}">
        @csrf
        <button class="btn btn-sm btn-outline-secondary">Mark all as read</button>
    </form>
</div>

<div class="list-group shadow-sm">
    @forelse($notifications as $notification)
        <a href="{{ route('notifications.open', $notification->id) }}"
           class="list-group-item list-group-item-action d-flex gap-3 align-items-center {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
            <i class="bi bi-{{ $notification->data['icon'] ?? 'bell' }} fs-5"></i>
            <div class="flex-grow-1">{{ $notification->data['message'] ?? 'Notification' }}</div>
            <small class="text-muted text-nowrap">{{ $notification->created_at->diffForHumans() }}</small>
        </a>
    @empty
        <div class="list-group-item text-center text-muted py-5">You're all caught up.</div>
    @endforelse
</div>

<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
