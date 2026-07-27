@php($material = $material ?? null)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mata Pelajaran / Kelas</label>
        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $material->course_id ?? '') == $course->id)>{{ $course->subject->name }} - {{ $course->classRoom->name }}</option>
            @endforeach
        </select>
        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tipe Materi</label>
        <select name="type" class="form-select">
            @foreach(['pdf'=>'PDF','word'=>'Word','ppt'=>'PPT','video'=>'Video Upload','image'=>'Gambar','link'=>'Video/Tautan Eksternal'] as $val => $label)
                <option value="{{ $val }}" @selected(old('type', $material->type ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="title" value="{{ old('title', $material->title ?? '') }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $material->description ?? '') }}</textarea>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Upload File</label>
        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if($material?->file_name)
            <div class="form-text">File saat ini: {{ $material->file_name }}</div>
        @endif
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">URL Video/Tautan</label>
        <input type="url" name="video_url" value="{{ old('video_url', $material->video_url ?? '') }}" class="form-control" placeholder="https://youtube.com/...">
    </div>
    <div class="col-12 mb-3 form-check">
        <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" @checked(old('is_published', $material->is_published ?? true))>
        <label class="form-check-label" for="is_published">Publikasikan ke siswa</label>
    </div>
</div>
