@extends('layouts.app')
@section('title', 'Penugasan Mengajar')
@section('page-title', 'Penugasan Mengajar')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari mata pelajaran" style="width:220px;">
                <select name="class_id" class="form-select form-select-sm" style="width:180px;">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Penugasan</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Mata Pelajaran</th><th>Guru</th><th>Kelas</th><th>Tahun Ajaran</th><th>Jml Siswa</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->subject->name }}</td>
                            <td>{{ $course->teacher->user->name }}</td>
                            <td>{{ $course->classRoom->name }}</td>
                            <td>{{ $course->academicYear->name }} ({{ $course->academicYear->semester }})</td>
                            <td>{{ $course->enrollments_count }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline" data-confirm-delete="Hapus penugasan ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-diagram-project"></i><p>Belum ada penugasan mengajar.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $courses->links() }}
    </div>
</div>
@endsection
