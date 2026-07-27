@php($classRoom = $classRoom ?? null)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Kelas (mis. X RPL 1)</label>
        <input type="text" name="name" value="{{ old('name', $classRoom->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tingkat</label>
        <select name="grade_level" class="form-select">
            @foreach(['X','XI','XII'] as $level)
                <option value="{{ $level }}" @selected(old('grade_level', $classRoom->grade_level ?? '') === $level)>{{ $level }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Jurusan</label>
        <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
            <option value="">-- Pilih Jurusan --</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected(old('department_id', $classRoom->department_id ?? '') == $dept->id)>{{ $dept->name }}</option>
            @endforeach
        </select>
        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tahun Ajaran</label>
        <select name="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror">
            <option value="">-- Pilih Tahun Ajaran --</option>
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}" @selected(old('academic_year_id', $classRoom->academic_year_id ?? '') == $year->id)>{{ $year->name }} ({{ $year->semester }})</option>
            @endforeach
        </select>
        @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Wali Kelas</label>
        <select name="homeroom_teacher_id" class="form-select">
            <option value="">-- Tidak Ada --</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $classRoom->homeroom_teacher_id ?? '') == $teacher->id)>{{ $teacher->user->name }}</option>
            @endforeach
        </select>
    </div>
</div>
