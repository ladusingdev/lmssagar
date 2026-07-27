@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-body p-4">
                <h5 class="mb-4"><i class="fa-solid fa-user me-2 text-secondary"></i>Informasi Profil</h5>

                <div class="text-center mb-4">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle" width="90" height="90" style="object-fit:cover;">
                </div>

                <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" name="avatar" class="form-control @error('avatar', 'updateProfileInformation') is-invalid @enderror" accept="image/*">
                        @error('avatar', 'updateProfileInformation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror">
                            @error('name', 'updateProfileInformation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror">
                            @error('email', 'updateProfileInformation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="L" @selected(auth()->user()->gender === 'L')>Laki-laki</option>
                                <option value="P" @selected(auth()->user()->gender === 'P')>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', auth()->user()->address) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
