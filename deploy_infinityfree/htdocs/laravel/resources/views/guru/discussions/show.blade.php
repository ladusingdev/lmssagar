@extends('layouts.app')
@section('title', $discussion->title)
@section('page-title', 'Diskusi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('guru.discussions.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <h5>{{ $discussion->title }}</h5>
        <p class="text-muted small">oleh {{ $discussion->user->name }} &mdash; {{ $discussion->created_at->diffForHumans() }}</p>
        <p>{{ $discussion->body }}</p>
        <hr>
        <h6>Komentar ({{ $discussion->comments->count() }})</h6>
        @foreach($discussion->comments as $comment)
            <div class="border rounded p-2 mb-2">
                <strong class="small">{{ $comment->user->name }}</strong>
                <p class="mb-1 small">{{ $comment->body }}</p>
                @foreach($comment->replies as $reply)
                    <div class="ms-4 border-start ps-2 mt-1">
                        <strong class="small">{{ $reply->user->name }}</strong>
                        <p class="mb-0 small">{{ $reply->body }}</p>
                    </div>
                @endforeach
            </div>
        @endforeach

        @unless($discussion->is_locked)
            <form method="POST" action="{{ route('guru.discussions.comments.store', $discussion) }}" class="mt-3">
                @csrf
                <div class="mb-2">
                    <textarea name="body" class="form-control" rows="2" placeholder="Tulis komentar..." required></textarea>
                </div>
                <button class="btn btn-brand btn-sm">Kirim Komentar</button>
            </form>
        @else
            <div class="alert alert-secondary small mt-3">Thread ini telah dikunci.</div>
        @endunless
    </div>
</div>
@endsection
