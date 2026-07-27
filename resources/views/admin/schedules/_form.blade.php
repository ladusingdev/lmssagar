@php($schedule = $schedule ?? null)
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kelas</label>
        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $schedule->class_id ?? '') == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
        @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Mata Pelajaran</label>
        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $schedule->course_id ?? '') == $course->id)>{{ $course->subject->name }} - {{ $course->classRoom->name }} ({{ $course->teacher->user->name }})</option>
            @endforeach
        </select>
        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun Ajaran</label>
        <select name="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}" @selected(old('academic_year_id', $schedule->academic_year_id ?? '') == $year->id)>{{ $year->name }} ({{ $year->semester }})</option>
            @endforeach
        </select>
        @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Hari</label>
        <select name="day_of_week" class="form-select">
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                <option value="{{ $day }}" @selected(old('day_of_week', $schedule->day_of_week ?? '') === $day)>{{ $day }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Jam Mulai</label>
        <input type="time" name="start_time" value="{{ old('start_time', $schedule->start_time ?? '') }}" class="form-control @error('start_time') is-invalid @enderror">
        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Jam Selesai</label>
        <input type="time" name="end_time" value="{{ old('end_time', $schedule->end_time ?? '') }}" class="form-control @error('end_time') is-invalid @enderror">
        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Ruang</label>
        <input type="text" name="room" value="{{ old('room', $schedule->room ?? '') }}" class="form-control">
    </div>
</div>
