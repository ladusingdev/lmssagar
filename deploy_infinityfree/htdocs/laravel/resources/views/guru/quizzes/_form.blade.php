@php($quiz = $quiz ?? null)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mata Pelajaran / Kelas</label>
        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $quiz->course_id ?? '') == $course->id)>{{ $course->subject->name }} - {{ $course->classRoom->name }}</option>
            @endforeach
        </select>
        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Judul Kuis</label>
        <input type="text" name="title" value="{{ old('title', $quiz->title ?? '') }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $quiz->description ?? '') }}</textarea>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Durasi (menit)</label>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $quiz->duration_minutes ?? 30) }}" class="form-control" min="1">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Waktu Mulai</label>
        <input type="datetime-local" name="start_time" value="{{ old('start_time', optional($quiz?->start_time)->format('Y-m-d\TH:i')) }}" class="form-control @error('start_time') is-invalid @enderror">
        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-5 mb-3">
        <label class="form-label">Waktu Selesai</label>
        <input type="datetime-local" name="end_time" value="{{ old('end_time', optional($quiz?->end_time)->format('Y-m-d\TH:i')) }}" class="form-control @error('end_time') is-invalid @enderror">
        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3 d-flex gap-4">
        <div class="form-check">
            <input type="checkbox" name="shuffle_questions" value="1" class="form-check-input" id="shuffle_questions" @checked($quiz->shuffle_questions ?? true)>
            <label class="form-check-label" for="shuffle_questions">Acak Soal</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="show_result_immediately" value="1" class="form-check-input" id="show_result_immediately" @checked($quiz->show_result_immediately ?? true)>
            <label class="form-check-label" for="show_result_immediately">Tampilkan Hasil Langsung</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" @checked(old('is_published', $quiz->is_published ?? true))>
            <label class="form-check-label" for="is_published">Publikasikan</label>
        </div>
    </div>
</div>
