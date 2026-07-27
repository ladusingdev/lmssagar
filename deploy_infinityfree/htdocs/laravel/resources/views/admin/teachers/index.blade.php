@extends('layouts.app')

@section('title', 'Manajemen Guru')
@section('page-title', 'Manajemen Guru')

@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama / NIP" style="width:220px;">
                <select name="status" class="form-select form-select-sm" style="width:160px;">
                    <option value="">Semua Status</option>
                    <option value="PNS" @selected(request('status')==='PNS')>PNS</option>
                    <option value="PPPK" @selected(request('status')==='PPPK')>PPPK</option>
                    <option value="Honorer" @selected(request('status')==='Honorer')>Honorer</option>
                    <option value="GTT" @selected(request('status')==='GTT')>GTT</option>
                    <option value="Kontrak" @selected(request('status')==='Kontrak')>Kontrak</option>
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Guru</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Status Kepegawaian</th>
                        <th>Status Akun</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td class="d-flex align-items-center gap-2">
                                <img src="{{ $teacher->user->avatar_url }}" class="avatar-circle">
                                {{ $teacher->user->name }}
                            </td>
                            <td>{{ $teacher->nip ?? '-' }}</td>
                            <td>{{ $teacher->user->email }}</td>
                            <td><span class="badge bg-secondary">{{ $teacher->employment_status }}</span></td>
                            <td>{!! $teacher->user->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>' !!}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="d-inline" data-confirm-delete="Hapus data guru ini beserta akunnya?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-chalkboard-user"></i><p>Belum ada data guru.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $teachers->links() }}
    </div>
</div>
@endsection
