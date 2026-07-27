@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="card fade-in">
    <div class="card-body p-0">
        @forelse($notifications as $notification)
            <a href="{{ route('notifications.open', $notification) }}" class="d-block text-decoration-none text-dark px-4 py-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
                <div class="d-flex justify-content-between">
                    <strong>{{ $notification->data['title'] ?? 'Notifikasi' }}</strong>
                    <span class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-muted small">{{ $notification->data['message'] ?? '' }}</div>
            </a>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-bell-slash"></i>
                <p>Belum ada notifikasi.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
