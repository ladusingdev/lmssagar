@extends('layouts.app')

@section('title', 'Manajemen Jurusan')
@section('page-title', 'Manajemen Jurusan')

@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari jurusan" style="width:220px;">
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <a href="{{ route('admin.departments.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Jurusan</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Kode</th><th>Nama Jurusan</th><th>Jumlah Kelas</th><th>Jumlah Siswa</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr>
                            <td><span class="badge bg-navy">{{ $department->code }}</span></td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->classes_count }}</td>
                            <td>{{ $department->students_count }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline" data-confirm-delete="Hapus jurusan ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-sitemap"></i><p>Belum ada data jurusan.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $departments->links() }}
    </div>
</div>
@endsection
