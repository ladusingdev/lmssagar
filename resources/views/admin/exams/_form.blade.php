@php($exam = $exam ?? null)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mata Pelajaran / Kelas</label>
        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $exam->course_id ?? '') == $course->id)>{{ $course->subject->name }} - {{ $course->classRoom->name }}</option>
            @endforeach
        </select>
        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Judul Ujian</label>
        <input type="text" name="title" value="{{ old('title', $exam->title ?? '') }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $exam->description ?? '') }}</textarea>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Durasi (menit)</label>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes ?? 60) }}" class="form-control" min="1">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Waktu Mulai</label>
        <input type="datetime-local" name="start_time" value="{{ old('start_time', optional($exam?->start_time)->format('Y-m-d\TH:i')) }}" class="form-control @error('start_time') is-invalid @enderror">
        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-5 mb-3">
        <label class="form-label">Waktu Selesai</label>
        <input type="datetime-local" name="end_time" value="{{ old('end_time', optional($exam?->end_time)->format('Y-m-d\TH:i')) }}" class="form-control @error('end_time') is-invalid @enderror">
        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Jumlah Soal Ditampilkan (acak, kosongkan = semua)</label>
        <input type="number" name="questions_to_show" value="{{ old('questions_to_show', $exam->questions_to_show ?? '') }}" class="form-control" min="1">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Nilai Kelulusan (KKM)</label>
        <input type="number" name="passing_score" value="{{ old('passing_score', $exam->passing_score ?? 75) }}" class="form-control" min="0" max="100">
    </div>
    <div class="col-12 mb-3 d-flex gap-4">
        <div class="form-check">
            <input type="checkbox" name="shuffle_questions" value="1" class="form-check-input" id="shuffle_questions" @checked($exam->shuffle_questions ?? true)>
            <label class="form-check-label" for="shuffle_questions">Acak Soal</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" @checked(old('is_published', $exam->is_published ?? true))>
            <label class="form-check-label" for="is_published">Publikasikan</label>
        </div>
    </div>
</div>
