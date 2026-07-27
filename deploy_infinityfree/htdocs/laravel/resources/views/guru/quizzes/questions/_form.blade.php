@php($question = $question ?? null)
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tipe Soal</label>
        <select name="type" id="questionType" class="form-select">
            <option value="multiple_choice" @selected(old('type', $question->type ?? '') === 'multiple_choice')>Pilihan Ganda</option>
            <option value="essay" @selected(old('type', $question->type ?? '') === 'essay')>Essay</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Skor</label>
        <input type="number" name="score" value="{{ old('score', $question->score ?? 10) }}" class="form-control" min="1" max="100">
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Pertanyaan</label>
        <textarea name="question" class="form-control @error('question') is-invalid @enderror" rows="3">{{ old('question', $question->question ?? '') }}</textarea>
        @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div id="mcOptions">
        @foreach(['A','B','C','D','E'] as $key)
            <div class="col-12 mb-2 d-flex align-items-center gap-2">
                <div class="form-check">
                    <input type="radio" name="correct_option" value="{{ $key }}" class="form-check-input" @checked(old('correct_option', $question->correct_option ?? '') === $key)>
                </div>
                <span class="fw-semibold">{{ $key }}</span>
                <input type="text" name="option_{{ strtolower($key) }}" value="{{ old('option_'.strtolower($key), $question->{'option_'.strtolower($key)} ?? '') }}" class="form-control" placeholder="Opsi {{ $key }}">
            </div>
        @endforeach
        <div class="form-text mb-3">Pilih radio button pada opsi jawaban yang benar.</div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var typeSelect = document.getElementById('questionType');
    var mcOptions = document.getElementById('mcOptions');
    function toggle() { mcOptions.style.display = typeSelect.value === 'essay' ? 'none' : ''; }
    typeSelect.addEventListener('change', toggle);
    toggle();
});
</script>
