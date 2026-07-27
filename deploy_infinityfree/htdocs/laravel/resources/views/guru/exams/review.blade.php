@extends('layouts.app')
@section('title', 'Review Jawaban')
@section('page-title', 'Review Jawaban: ' . $attempt->student->user->name)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('guru.exams.results', $exam) }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <form method="POST" action="{{ route('guru.exams.attempts.review.update', [$exam, $attempt]) }}">
            @csrf @method('PUT')
            @foreach($attempt->answers as $answer)
                <div class="border rounded p-3 mb-2">
                    <span class="badge bg-secondary mb-2">{{ $answer->question->type === 'essay' ? 'Essay' : 'Pilihan Ganda' }} - Maks {{ $answer->question->score }} poin</span>
                    <p class="mb-2">{{ $answer->question->question }}</p>
                    @if($answer->question->type === 'multiple_choice')
                        <p class="small">Jawaban siswa: <strong>{{ $answer->selected_option ?? '-' }}</strong> | Kunci: <strong>{{ $answer->question->correct_option }}</strong></p>
                        <p class="small">Skor: {{ $answer->score ?? 0 }}</p>
                    @else
                        <p class="small fst-italic bg-light p-2 rounded">{{ $answer->answer_text ?? '(tidak dijawab)' }}</p>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label small">Skor (maks {{ $answer->question->score }})</label>
                                <input type="number" name="scores[{{ $answer->id }}]" value="{{ $answer->score }}" class="form-control form-control-sm" min="0" max="{{ $answer->question->score }}">
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
            <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Nilai Essay</button>
        </form>
    </div>
</div>
@endsection
