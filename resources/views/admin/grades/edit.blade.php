@extends('layouts.app')
@section('title', 'Edit Nilai')
@section('page-title', 'Edit Nilai Akhir')
@section('content')
<div class="card fade-in"><div class="card-body">
    <p class="text-muted">{{ $grade->student->user->name }} &mdash; {{ $grade->course->subject->name }} ({{ $grade->course->classRoom->name }})</p>
    <div class="row mb-3">
        <div class="col-md-4"><div class="section-card border"><div class="small text-muted">Rata-rata Tugas</div><div class="fs-4 fw-semibold">{{ $grade->assignment_score ? number_format($grade->assignment_score,1) : '-' }}</div></div></div>
        <div class="col-md-4"><div class="section-card border"><div class="small text-muted">Rata-rata Kuis</div><div class="fs-4 fw-semibold">{{ $grade->quiz_score ? number_format($grade->quiz_score,1) : '-' }}</div></div></div>
        <div class="col-md-4"><div class="section-card border"><div class="small text-muted">Rata-rata Ujian</div><div class="fs-4 fw-semibold">{{ $grade->exam_score ? number_format($grade->exam_score,1) : '-' }}</div></div></div>
    </div>
    <form method="POST" action="{{ route('admin.grades.update', $grade) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nilai Akhir</label>
            <input type="number" step="0.01" name="final_score" value="{{ old('final_score', $grade->final_score) }}" class="form-control @error('final_score') is-invalid @enderror" min="0" max="100">
            @error('final_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $grade->notes) }}</textarea>
        </div>
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.grades.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
