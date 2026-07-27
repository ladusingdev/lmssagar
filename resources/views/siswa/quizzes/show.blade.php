@extends('layouts.app')
@section('title', $quiz->title)
@section('page-title', $quiz->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('siswa.quizzes.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <p class="text-muted small">{{ $quiz->course->subject->name }}</p>
        <p>{{ $quiz->description }}</p>
        <ul class="list-unstyled small">
            <li><strong>Durasi:</strong> {{ $quiz->duration_minutes }} menit</li>
            <li><strong>Waktu Tersedia:</strong> {{ $quiz->start_time->format('d M Y H:i') }} - {{ $quiz->end_time->format('d M Y H:i') }}</li>
            <li><strong>Jumlah Soal:</strong> {{ $quiz->questions()->count() }}</li>
        </ul>

        @if(! $attempt)
            @if($quiz->isOpen())
                <form method="POST" action="{{ route('siswa.quizzes.start', $quiz) }}">
                    @csrf
                    <button class="btn btn-brand"><i class="fa-solid fa-play me-1"></i>Mulai Kuis</button>
                </form>
            @else
                <div class="alert alert-secondary small mb-0">Kuis belum dibuka atau sudah ditutup.</div>
            @endif
        @elseif($attempt->status === 'in_progress')
            <a href="{{ route('siswa.quizzes.attempt', $quiz) }}" class="btn btn-brand"><i class="fa-solid fa-play me-1"></i>Lanjutkan Kuis</a>
        @else
            <a href="{{ route('siswa.quizzes.result', $quiz) }}" class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Lihat Hasil</a>
        @endif
    </div>
</div>
@endsection
