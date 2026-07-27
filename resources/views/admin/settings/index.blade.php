@extends('layouts.app')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" name="school_name" value="{{ old('school_name', \App\Models\Setting::get('school_name', 'SMKN 9 Garut')) }}" class="form-control @error('school_name') is-invalid @enderror">
                    @error('school_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kepala Sekolah</label>
                    <input type="text" name="principal_name" value="{{ old('principal_name', \App\Models\Setting::get('principal_name')) }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="school_phone" value="{{ old('school_phone', \App\Models\Setting::get('school_phone')) }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Sekolah</label>
                    <input type="email" name="school_email" value="{{ old('school_email', \App\Models\Setting::get('school_email')) }}" class="form-control">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat Sekolah</label>
                    <textarea name="school_address" class="form-control" rows="2">{{ old('school_address', \App\Models\Setting::get('school_address')) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo Sekolah</label>
                    <input type="file" name="logo" class="form-control">
                    @if(\App\Models\Setting::get('school_logo'))
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::get('school_logo')) }}" class="mt-2" height="60">
                    @endif
                </div>
            </div>
            <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Pengaturan</button>
        </form>
    </div>
</div>
@endsection
