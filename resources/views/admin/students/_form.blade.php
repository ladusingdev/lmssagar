@php($student = $student ?? null)
@php($user = $student?->user)
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
        <label class="form-label">Password {{ $student ? '(opsional)' : '' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">No. Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">NISN</label>
        <input type="text" name="nisn" value="{{ old('nisn', $student->nisn ?? '') }}" class="form-control @error('nisn') is-invalid @enderror">
        @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">NIS</label>
        <input type="text" name="nis" value="{{ old('nis', $student->nis ?? '') }}" class="form-control @error('nis') is-invalid @enderror">
        @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Kelas</label>
        <select name="class_id" class="form-select">
            <option value="">-- Pilih Kelas --</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id ?? '') == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Jurusan</label>
        <select name="department_id" class="form-select">
            <option value="">-- Pilih Jurusan --</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected(old('department_id', $student->department_id ?? '') == $dept->id)>{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Jenis Kelamin</label>
        <select name="gender" class="form-select">
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('gender', $user->gender ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('gender', $user->gender ?? '') === 'P')>Perempuan</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Tempat Lahir</label>
        <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', optional($student?->birth_date)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Tahun Masuk</label>
        <input type="number" name="admission_year" value="{{ old('admission_year', $student->admission_year ?? date('Y')) }}" class="form-control" min="2000" max="2100">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Nama Orang Tua/Wali</label>
        <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">No. Telepon Orang Tua</label>
        <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Status Siswa</label>
        <select name="status" class="form-select">
            @foreach(['active'=>'Aktif','graduated'=>'Lulus','dropout'=>'Keluar','transferred'=>'Pindah'] as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $student->status ?? 'active') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Alamat</label>
        <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address ?? '') }}</textarea>
    </div>
    @if($student)
        <div class="col-12 mb-3 form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked($user->is_active)>
            <label class="form-check-label" for="is_active">Akun Aktif</label>
        </div>
    @endif
</div>
