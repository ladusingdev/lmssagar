@php($academicYear = $academicYear ?? null)
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun Ajaran (mis. 2025/2026)</label>
        <input type="text" name="name" value="{{ old('name', $academicYear->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" placeholder="2025/2026">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Semester</label>
        <select name="semester" class="form-select">
            <option value="Ganjil" @selected(old('semester', $academicYear->semester ?? '') === 'Ganjil')>Ganjil</option>
            <option value="Genap" @selected(old('semester', $academicYear->semester ?? '') === 'Genap')>Genap</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="start_date" value="{{ old('start_date', optional($academicYear?->start_date)->format('Y-m-d')) }}" class="form-control @error('start_date') is-invalid @enderror">
        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2 mb-3">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" name="end_date" value="{{ old('end_date', optional($academicYear?->end_date)->format('Y-m-d')) }}" class="form-control @error('end_date') is-invalid @enderror">
        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
