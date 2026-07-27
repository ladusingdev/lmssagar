@php($teacher = $teacher ?? null)
@php($user = $teacher?->user)
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Password {{ $teacher ? '(opsional)' : '' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ $teacher ? 'Kosongkan jika tidak diubah' : '' }}">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">No. Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">NIP</label>
        <input type="text" name="nip" value="{{ old('nip', $teacher->nip ?? '') }}" class="form-control @error('nip') is-invalid @enderror">
        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">NUPTK</label>
        <input type="text" name="nuptk" value="{{ old('nuptk', $teacher->nuptk ?? '') }}" class="form-control @error('nuptk') is-invalid @enderror">
        @error('nuptk')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Status Kepegawaian</label>
        <select name="employment_status" class="form-select">
            @foreach(['PNS','PPPK','Honorer','GTT','Kontrak'] as $status)
                <option value="{{ $status }}" @selected(old('employment_status', $teacher->employment_status ?? '') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Jenis Kelamin</label>
        <select name="gender" class="form-select">
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('gender', $user->gender ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('gender', $user->gender ?? '') === 'P')>Perempuan</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tempat Lahir</label>
        <input type="text" name="birth_place" value="{{ old('birth_place', $teacher->birth_place ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', optional($teacher?->birth_date)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Alamat</label>
        <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address ?? '') }}</textarea>
    </div>
    @if($teacher)
        <div class="col-12 mb-3 form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked($user->is_active)>
            <label class="form-check-label" for="is_active">Akun Aktif</label>
        </div>
    @endif
</div>
