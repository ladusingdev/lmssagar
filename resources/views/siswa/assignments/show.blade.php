@extends('layouts.app')
@section('title', $assignment->title)
@section('page-title', $assignment->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('siswa.assignments.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <p class="text-muted small">{{ $assignment->course->subject->name }} &mdash; Deadline: {{ $assignment->deadline->format('d M Y H:i') }}</p>
        <p>{{ $assignment->description }}</p>
        @if($assignment->attachment_path)
            <p><a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($assignment->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-paperclip me-1"></i>Lampiran Soal</a></p>
        @endif

        <hr>

        @if($submission)
            <div class="alert alert-info">
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($submission->status) }}</p>
                <p class="mb-1"><strong>Dikumpulkan:</strong> {{ $submission->submitted_at->format('d M Y H:i') }}</p>
                <p class="mb-1"><strong>File:</strong> {{ $submission->file_name }}</p>
                @if($submission->score !== null)
                    <p class="mb-1"><strong>Nilai:</strong> {{ $submission->score }} / {{ $assignment->max_score }}</p>
                    <p class="mb-0"><strong>Komentar Guru:</strong> {{ $submission->feedback ?? '-' }}</p>
                @endif
            </div>
        @endif

        @if(! $submission || $submission->score === null)
            @if($assignment->allow_late || ! $assignment->isPastDeadline())
                <form method="POST" action="{{ route('siswa.assignments.submit', $assignment) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload Jawaban</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="note" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-brand"><i class="fa-solid fa-upload me-1"></i>{{ $submission ? 'Kumpulkan Ulang' : 'Kumpulkan Tugas' }}</button>
                </form>
            @else
                <div class="alert alert-danger small mb-0">Batas waktu pengumpulan telah berakhir.</div>
            @endif
        @endif
    </div>
</div>
@endsection
