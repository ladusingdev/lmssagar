@extends('layouts.app')
@section('title', 'Tahun Ajaran')
@section('page-title', 'Manajemen Tahun Ajaran')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex gap-2" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari tahun ajaran" style="width:220px;">
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <a href="{{ route('admin.academic-years.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Tahun Ajaran</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Tahun Ajaran</th><th>Semester</th><th>Mulai</th><th>Selesai</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($academicYears as $year)
                        <tr>
                            <td>{{ $year->name }}</td>
                            <td>{{ $year->semester }}</td>
                            <td>{{ $year->start_date->format('d M Y') }}</td>
                            <td>{{ $year->end_date->format('d M Y') }}</td>
                            <td>
                                @if($year->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <form action="{{ route('admin.academic-years.activate', $year) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary">Aktifkan</button>
                                    </form>
                                @endif
                            </td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.academic-years.edit', $year) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.academic-years.destroy', $year) }}" method="POST" class="d-inline" data-confirm-delete="Hapus tahun ajaran ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-calendar-days"></i><p>Belum ada data tahun ajaran.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $academicYears->links() }}
    </div>
</div>
@endsection
