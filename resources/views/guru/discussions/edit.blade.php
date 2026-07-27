@extends('layouts.app')
@section('title', 'Edit Thread')
@section('page-title', 'Edit Thread Diskusi')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.discussions.update', $discussion) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" value="{{ old('title', $discussion->title) }}" class="form-control @error('title') is-invalid @enderror">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="4">{{ old('body', $discussion->body) }}</textarea>
            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_locked" value="1" class="form-check-input" id="is_locked" @checked($discussion->is_locked)>
            <label class="form-check-label" for="is_locked">Kunci Thread (tidak menerima komentar baru)</label>
        </div>
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.discussions.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
