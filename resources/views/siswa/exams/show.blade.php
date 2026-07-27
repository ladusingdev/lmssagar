@extends('layouts.app')
@section('title', $exam->title)
@section('page-title', $exam->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('siswa.exams.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <p class="text-muted small">{{ $exam->course->subject->name }}</p>
        <p>{{ $exam->description }}</p>
        <ul class="list-unstyled small">
            <li><strong>Durasi:</strong> {{ $exam->duration_minutes }} menit</li>
            <li><strong>Waktu Tersedia:</strong> {{ $exam->start_time->format('d M Y H:i') }} - {{ $exam->end_time->format('d M Y H:i') }}</li>
            <li><strong>Jumlah Soal:</strong> {{ $exam->questions_to_show ?? $exam->questions()->count() }}</li>
            <li><strong>Nilai Kelulusan (KKM):</strong> {{ $exam->passing_score }}</li>
        </ul>

        <div class="alert alert-warning small"><i class="fa-solid fa-triangle-exclamation me-1"></i>Ujian akan otomatis dikumpulkan saat waktu habis. Pastikan koneksi internet Anda stabil.</div>

        @if(! $attempt)
            @if($exam->isOpen())
                <form method="POST" action="{{ route('siswa.exams.start', $exam) }}">
                    @csrf
                    <button class="btn btn-brand"><i class="fa-solid fa-play me-1"></i>Mulai Ujian</button>
                </form>
            @else
                <div class="alert alert-secondary small mb-0">Ujian belum dibuka atau sudah ditutup.</div>
            @endif
        @elseif($attempt->status === 'in_progress')
            <a href="{{ route('siswa.exams.attempt', $exam) }}" class="btn btn-brand"><i class="fa-solid fa-play me-1"></i>Lanjutkan Ujian</a>
        @else
            <a href="{{ route('siswa.exams.result', $exam) }}" class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Lihat Hasil</a>
        @endif
    </div>
</div>
@endsection
