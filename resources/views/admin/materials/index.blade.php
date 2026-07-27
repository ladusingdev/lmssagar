@extends('layouts.app')
@section('title', 'Materi Pembelajaran')
@section('page-title', 'Manajemen Materi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari judul materi" style="width:220px;">
                <select name="type" class="form-select form-select-sm" style="width:150px;">
                    <option value="">Semua Tipe</option>
                    @foreach(['pdf','word','ppt','video','image','link'] as $type)
                        <option value="{{ $type }}" @selected(request('type')===$type)>{{ strtoupper($type) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
            <a href="{{ route('admin.materials.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Materi</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel / Kelas</th><th>Guru</th><th>Tipe</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr>
                            <td>{{ $material->title }}</td>
                            <td>{{ $material->course->subject->name }} - {{ $material->course->classRoom->name }}</td>
                            <td>{{ $material->teacher->user->name }}</td>
                            <td><span class="badge bg-secondary">{{ strtoupper($material->type) }}</span></td>
                            <td>{!! $material->is_published ? '<span class="badge bg-success">Publish</span>' : '<span class="badge bg-warning text-dark">Draft</span>' !!}</td>
                            <td class="text-end table-actions">
                                @if($material->file_path)
                                    <a href="{{ route('admin.materials.download', $material) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-download"></i></a>
                                @endif
                                <a href="{{ route('admin.materials.edit', $material) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="d-inline" data-confirm-delete="Hapus materi ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-file-lines"></i><p>Belum ada data materi.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $materials->links() }}
    </div>
</div>
@endsection
