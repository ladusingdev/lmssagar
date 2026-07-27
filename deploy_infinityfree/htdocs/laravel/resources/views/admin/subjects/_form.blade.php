@php($subject = $subject ?? null)
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode Mapel</label>
        <input type="text" name="code" value="{{ old('code', $subject->code ?? '') }}" class="form-control @error('code') is-invalid @enderror">
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Mata Pelajaran</label>
        <input type="text" name="name" value="{{ old('name', $subject->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $subject->description ?? '') }}</textarea>
    </div>
</div>
