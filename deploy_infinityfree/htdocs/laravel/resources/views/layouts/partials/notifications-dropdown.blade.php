@php
    $unreadNotifications = auth()->user()->unreadNotifications()->take(6)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp
<div class="dropdown">
    <button class="btn position-relative border-0" type="button" data-bs-toggle="dropdown">
        <i class="fa-solid fa-bell fs-5 text-secondary"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end p-0" style="width: 320px;">
        <div class="px-3 py-2 border-bottom fw-semibold">Notifikasi</div>
        <div style="max-height: 320px; overflow-y:auto;">
            @forelse($unreadNotifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}" class="dropdown-item py-2 border-bottom small white-space-normal">
                    <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notifikasi' }}</div>
                    <div class="text-muted">{{ $notification->data['message'] ?? '' }}</div>
                    <div class="text-muted" style="font-size:.7rem;">{{ $notification->created_at->diffForHumans() }}</div>
                </a>
            @empty
                <div class="text-center text-muted small py-4">Tidak ada notifikasi baru</div>
            @endforelse
        </div>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}" class="border-top">
                @csrf
                <button class="dropdown-item text-center text-primary small py-2">Tandai semua dibaca</button>
            </form>
        @endif
    </div>
</div>
