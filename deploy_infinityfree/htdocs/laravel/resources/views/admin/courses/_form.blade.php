@php($course = $course ?? null)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mata Pelajaran</label>
        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
            <option value="">-- Pilih Mata Pelajaran --</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" @selected(old('subject_id', $course->subject_id ?? '') == $subject->id)>{{ $subject->name }}</option>
            @endforeach
        </select>
        @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Guru Pengampu</label>
        <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
            <option value="">-- Pilih Guru --</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $course->teacher_id ?? '') == $teacher->id)>{{ $teacher->user->name }}</option>
            @endforeach
        </select>
        @error('teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Kelas</label>
        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror">
            <option value="">-- Pilih Kelas --</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $course->class_id ?? '') == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
        @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tahun Ajaran</label>
        <select name="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror">
            <option value="">-- Pilih Tahun Ajaran --</option>
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}" @selected(old('academic_year_id', $course->academic_year_id ?? '') == $year->id)>{{ $year->name }} ({{ $year->semester }})</option>
            @endforeach
        </select>
        @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @if(!$course)
        <div class="col-12"><div class="alert alert-info small mb-0"><i class="fa-solid fa-circle-info me-1"></i>Seluruh siswa pada kelas yang dipilih akan otomatis terdaftar (enrollment) pada mata pelajaran ini.</div></div>
    @endif
</div>
