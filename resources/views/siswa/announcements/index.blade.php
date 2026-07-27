@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        @forelse($announcements as $announcement)
            <div class="border-bottom py-3">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-1">{{ $announcement->title }}</h6>
                    <span class="badge {{ $announcement->type === 'sekolah' ? 'bg-navy' : 'bg-secondary' }}">{{ $announcement->type === 'sekolah' ? 'Sekolah' : 'Guru' }}</span>
                </div>
                <p class="text-muted small mb-1">oleh {{ $announcement->user->name }} &mdash; {{ $announcement->published_at?->format('d M Y H:i') }}</p>
                <p class="mb-0">{{ $announcement->content }}</p>
            </div>
        @empty
            <div class="empty-state"><i class="fa-solid fa-bullhorn"></i><p>Belum ada pengumuman.</p></div>
        @endforelse
        {{ $announcements->links() }}
    </div>
</div>
@endsection
