@extends('layouts.app')
@section('title', 'Nilai Tugas')
@section('page-title', 'Nilai Tugas')
@section('content')
<div class="card fade-in"><div class="card-body">
    <p class="text-muted">{{ $submission->student->user->name }} &mdash; {{ $submission->assignment->title }}</p>
    @if($submission->note)
        <div class="alert alert-secondary small"><strong>Catatan siswa:</strong> {{ $submission->note }}</div>
    @endif
    @if($submission->file_path)
        <p><a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-download me-1"></i>Unduh Jawaban</a></p>
    @endif

    <form method="POST" action="{{ route('guru.submissions.grade.update', $submission) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nilai (maks {{ $submission->assignment->max_score }})</label>
            <input type="number" name="score" value="{{ old('score', $submission->score) }}" class="form-control @error('score') is-invalid @enderror" min="0" max="{{ $submission->assignment->max_score }}">
            @error('score')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Komentar Guru</label>
            <textarea name="feedback" class="form-control" rows="4">{{ old('feedback', $submission->feedback) }}</textarea>
        </div>
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Nilai</button>
        <a href="{{ route('guru.assignments.submissions.index', $submission->assignment) }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
