@extends('layouts.app')
@section('title', $discussion->title)
@section('page-title', 'Detail Diskusi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('admin.discussions.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <h5>{{ $discussion->title }}</h5>
        <p class="text-muted small">{{ $discussion->course->subject->name }} &mdash; oleh {{ $discussion->user->name }} &mdash; {{ $discussion->created_at->diffForHumans() }}</p>
        <p>{{ $discussion->body }}</p>
        <hr>
        <h6>Komentar ({{ $discussion->comments->count() }})</h6>
        @foreach($discussion->comments as $comment)
            <div class="border rounded p-2 mb-2">
                <div class="d-flex justify-content-between">
                    <strong class="small">{{ $comment->user->name }}</strong>
                    <form action="{{ route('admin.discussions.comments.destroy', [$discussion, $comment]) }}" method="POST" data-confirm-delete="Hapus komentar ini?">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
                <p class="mb-1 small">{{ $comment->body }}</p>
                @foreach($comment->replies as $reply)
                    <div class="ms-4 border-start ps-2 mt-1">
                        <strong class="small">{{ $reply->user->name }}</strong>
                        <p class="mb-0 small">{{ $reply->body }}</p>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endsection
