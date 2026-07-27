@php($department = $department ?? null)
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode Jurusan</label>
        <input type="text" name="code" value="{{ old('code', $department->code ?? '') }}" class="form-control @error('code') is-invalid @enderror">
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Jurusan</label>
        <input type="text" name="name" value="{{ old('name', $department->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description ?? '') }}</textarea>
    </div>
</div>
