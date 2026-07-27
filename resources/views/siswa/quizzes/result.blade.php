@extends('layouts.app')
@section('title', 'Hasil Kuis')
@section('page-title', 'Hasil: ' . $quiz->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('siswa.quizzes.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>

        <div class="text-center mb-4">
            <div class="display-6 fw-bold text-navy">{{ $attempt->score ?? '-' }} / {{ $quiz->totalScore() }}</div>
            <span class="badge {{ $attempt->status === 'graded' ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ $attempt->status === 'graded' ? 'Sudah Dinilai' : 'Menunggu Penilaian Essay' }}
            </span>
        </div>

        @if($quiz->show_result_immediately)
            @foreach($attempt->answers as $answer)
                <div class="border rounded p-3 mb-2">
                    <p class="mb-2">{{ $answer->question->question }}</p>
                    @if($answer->question->type === 'multiple_choice')
                        <p class="small mb-1">Jawaban Anda: <strong>{{ $answer->selected_option ?? '-' }}</strong> {!! $answer->is_correct ? '<span class="text-success">(Benar)</span>' : '<span class="text-danger">(Salah)</span>' !!}</p>
                        <p class="small mb-0">Kunci Jawaban: <strong>{{ $answer->question->correct_option }}</strong></p>
                    @else
                        <p class="small fst-italic bg-light p-2 rounded mb-1">{{ $answer->answer_text ?? '(tidak dijawab)' }}</p>
                        <p class="small mb-0">Skor: {{ $answer->score ?? 'Menunggu penilaian' }}</p>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
