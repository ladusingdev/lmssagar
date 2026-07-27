@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Manajemen Mata Pelajaran')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari mata pelajaran" style="width:220px;">
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Mapel</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Kode</th><th>Nama</th><th>Jumlah Kelas Diampu</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td><span class="badge bg-navy">{{ $subject->code }}</span></td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->courses_count }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="d-inline" data-confirm-delete="Hapus mata pelajaran ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state"><i class="fa-solid fa-book"></i><p>Belum ada data mata pelajaran.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $subjects->links() }}
    </div>
</div>
@endsection
