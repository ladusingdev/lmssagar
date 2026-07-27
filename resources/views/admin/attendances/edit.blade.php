@extends('layouts.app')
@section('title', 'Edit Presensi')
@section('page-title', 'Edit Presensi')
@section('content')
<div class="card fade-in"><div class="card-body">
    <p class="text-muted">{{ $attendance->student->user->name }} &mdash; {{ $attendance->date->format('d M Y') }}</p>
    <form method="POST" action="{{ route('admin.attendances.update', $attendance) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                @foreach(['hadir'=>'Hadir','izin'=>'Izin','sakit'=>'Sakit','alpha'=>'Alpha'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $attendance->status) === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="note" class="form-control" rows="3">{{ old('note', $attendance->note) }}</textarea>
        </div>
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
