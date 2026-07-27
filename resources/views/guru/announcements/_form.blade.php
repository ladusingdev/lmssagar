@php($announcement = $announcement ?? null)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mata Pelajaran / Kelas Tujuan</label>
        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $announcement->course_id ?? '') == $course->id)>{{ $course->subject->name }} - {{ $course->classRoom->name }}</option>
            @endforeach
        </select>
        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" @checked(old('is_published', $announcement->is_published ?? true))>
            <label class="form-check-label" for="is_published">Publikasikan Sekarang</label>
        </div>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Isi Pengumuman</label>
        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5">{{ old('content', $announcement->content ?? '') }}</textarea>
        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
