@php($assignment = $assignment ?? null)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mata Pelajaran / Kelas</label>
        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $assignment->course_id ?? '') == $course->id)>{{ $course->subject->name }} - {{ $course->classRoom->name }}</option>
            @endforeach
        </select>
        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Deadline</label>
        <input type="datetime-local" name="deadline" value="{{ old('deadline', optional($assignment?->deadline)->format('Y-m-d\TH:i')) }}" class="form-control @error('deadline') is-invalid @enderror">
        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Judul Tugas</label>
        <input type="text" name="title" value="{{ old('title', $assignment->title ?? '') }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Instruksi / Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $assignment->description ?? '') }}</textarea>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Lampiran (opsional)</label>
        <input type="file" name="attachment" class="form-control">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Nilai Maksimal</label>
        <input type="number" name="max_score" value="{{ old('max_score', $assignment->max_score ?? 100) }}" min="1" max="100" class="form-control">
    </div>
    <div class="col-md-4 mb-3 d-flex align-items-end gap-4">
        <div class="form-check">
            <input type="checkbox" name="allow_late" value="1" class="form-check-input" id="allow_late" @checked($assignment->allow_late ?? false)>
            <label class="form-check-label" for="allow_late">Izinkan Telat</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" @checked(old('is_published', $assignment->is_published ?? true))>
            <label class="form-check-label" for="is_published">Publikasikan</label>
        </div>
    </div>
</div>
