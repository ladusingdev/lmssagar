@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama / email" style="width:220px;">
                <select name="role" class="form-select form-select-sm" style="width:160px;">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role')==='admin')>Admin</option>
                    <option value="guru" @selected(request('role')==='guru')>Guru</option>
                    <option value="siswa" @selected(request('role')==='siswa')>Siswa</option>
                </select>
                <select name="status" class="form-select form-select-sm" style="width:150px;">
                    <option value="">Semua Status</option>
                    <option value="1" @selected(request('status')==='1')>Aktif</option>
                    <option value="0" @selected(request('status')==='0')>Nonaktif</option>
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Admin</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Login Terakhir</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="d-flex align-items-center gap-2">
                                <img src="{{ $user->avatar_url }}" class="avatar-circle">
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($user->roles->pluck('name')->first() ?? '-') }}</span></td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $user->last_login_at?->diffForHumans() ?? '-' }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" data-confirm-delete="Hapus pengguna ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-users"></i><p>Tidak ada data pengguna.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>
@endsection
