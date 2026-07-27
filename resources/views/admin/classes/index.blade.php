@extends('layouts.app')
@section('title', 'Manajemen Kelas')
@section('page-title', 'Manajemen Kelas')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari kelas" style="width:200px;">
                <select name="department_id" class="form-select form-select-sm" style="width:200px;">
                    <option value="">Semua Jurusan</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
            <a href="{{ route('admin.classes.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Kelas</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Nama Kelas</th><th>Tingkat</th><th>Jurusan</th><th>Tahun Ajaran</th><th>Wali Kelas</th><th>Jml Siswa</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->grade_level }}</td>
                            <td>{{ $class->department->name }}</td>
                            <td>{{ $class->academicYear->name }} ({{ $class->academicYear->semester }})</td>
                            <td>{{ $class->homeroomTeacher->user->name ?? '-' }}</td>
                            <td>{{ $class->students_count }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="d-inline" data-confirm-delete="Hapus kelas ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-school"></i><p>Belum ada data kelas.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $classes->links() }}
    </div>
</div>
@endsection
