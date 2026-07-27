@extends('layouts.app')
@section('title', $quiz->title)
@section('page-title', 'Mengerjakan: ' . $quiz->title)
@section('content')
<div class="card fade-in mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <strong>Sisa Waktu:</strong>
            <span id="countdown" class="fs-5 fw-bold text-navy ms-2">--:--:--</span>
        </div>
        <div class="text-muted small">{{ $questions->count() }} soal</div>
    </div>
</div>

<form method="POST" action="{{ route('siswa.quizzes.submit', $quiz) }}" id="quizForm" data-confirm-delete="Kumpulkan kuis sekarang? Jawaban tidak dapat diubah setelah dikumpulkan.">
    @csrf
    @foreach($questions as $i => $question)
        @php($answer = $answers->get($question->id))
        <div class="card fade-in mb-2">
            <div class="card-body">
                <span class="badge bg-secondary mb-2">Soal {{ $i + 1 }} - {{ $question->score }} poin</span>
                <p>{{ $question->question }}</p>
                @if($question->type === 'multiple_choice')
                    @foreach($question->options() as $key => $option)
                        <div class="form-check">
                            <input type="radio" class="form-check-input quiz-option" name="q{{ $question->id }}" value="{{ $key }}" data-question="{{ $question->id }}" @checked($answer?->selected_option === $key)>
                            <label class="form-check-label">{{ $key }}. {{ $option }}</label>
                        </div>
                    @endforeach
                @else
                    <textarea class="form-control quiz-essay" data-question="{{ $question->id }}" rows="3" placeholder="Tulis jawaban Anda...">{{ $answer?->answer_text }}</textarea>
                @endif
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-brand"><i class="fa-solid fa-paper-plane me-1"></i>Kumpulkan Kuis</button>
</form>
@endsection

@push('scripts')
<script>
const answerUrl = @json(route('siswa.quizzes.answer', $quiz));
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function saveAnswer(payload) {
    fetch(answerUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(payload),
    });
}

document.querySelectorAll('.quiz-option').forEach(function (el) {
    el.addEventListener('change', function () {
        saveAnswer({ question_id: el.dataset.question, selected_option: el.value });
    });
});

document.querySelectorAll('.quiz-essay').forEach(function (el) {
    el.addEventListener('blur', function () {
        saveAnswer({ question_id: el.dataset.question, answer_text: el.value });
    });
});

startCountdown(new Date(@json($endsAt->toIso8601String())).getTime(), 'countdown', 'quizForm');
</script>
@endpush
