@extends('layouts.app')
@section('title', 'Hasil Ujian')
@section('page-title', 'Hasil: ' . $exam->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('siswa.exams.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>

        <div class="text-center mb-4">
            <div class="display-6 fw-bold text-navy">{{ $attempt->score ?? '-' }}</div>
            @if($attempt->is_passed === null)
                <span class="badge bg-warning text-dark">Menunggu Penilaian Essay</span>
            @elseif($attempt->is_passed)
                <span class="badge bg-success">Lulus</span>
            @else
                <span class="badge bg-danger">Tidak Lulus</span>
            @endif
        </div>

        @foreach($attempt->answers as $answer)
            <div class="border rounded p-3 mb-2">
                <p class="mb-2">{{ $answer->question->question }}</p>
                @if($answer->question->type === 'multiple_choice')
                    <p class="small mb-1">Jawaban Anda: <strong>{{ $answer->selected_option ?? '-' }}</strong> {!! $answer->is_correct ? '<span class="text-success">(Benar)</span>' : '<span class="text-danger">(Salah)</span>' !!}</p>
                @else
                    <p class="small fst-italic bg-light p-2 rounded mb-1">{{ $answer->answer_text ?? '(tidak dijawab)' }}</p>
                    <p class="small mb-0">Skor: {{ $answer->score ?? 'Menunggu penilaian' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
