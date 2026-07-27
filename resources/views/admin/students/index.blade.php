@extends('layouts.app')
@section('title', 'Manajemen Siswa')
@section('page-title', 'Manajemen Siswa')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama / NISN / NIS" style="width:220px;">
                <select name="class_id" class="form-select form-select-sm" style="width:180px;">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select form-select-sm" style="width:150px;">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status')==='active')>Aktif</option>
                    <option value="graduated" @selected(request('status')==='graduated')>Lulus</option>
                    <option value="dropout" @selected(request('status')==='dropout')>Keluar</option>
                    <option value="transferred" @selected(request('status')==='transferred')>Pindah</option>
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
            <a href="{{ route('admin.students.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Siswa</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Nama</th><th>NISN</th><th>Kelas</th><th>Jurusan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="d-flex align-items-center gap-2">
                                <img src="{{ $student->user->avatar_url }}" class="avatar-circle">
                                {{ $student->user->name }}
                            </td>
                            <td>{{ $student->nisn ?? '-' }}</td>
                            <td>{{ $student->classRoom->name ?? '-' }}</td>
                            <td>{{ $student->department->name ?? '-' }}</td>
                            <td>
                                @php($statusLabel = ['active'=>'Aktif','graduated'=>'Lulus','dropout'=>'Keluar','transferred'=>'Pindah'][$student->status] ?? $student->status)
                                <span class="badge {{ $student->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline" data-confirm-delete="Hapus data siswa ini beserta akunnya?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-user-graduate"></i><p>Belum ada data siswa.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $students->links() }}
    </div>
</div>
@endsection
