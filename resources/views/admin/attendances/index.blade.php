@extends('layouts.app')
@section('title', 'Manajemen Presensi')
@section('page-title', 'Manajemen Presensi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form class="d-flex gap-2 flex-wrap mb-3" method="GET">
            <select name="class_id" class="form-select form-select-sm" style="width:180px;">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm" style="width:170px;">
            <select name="status" class="form-select form-select-sm" style="width:150px;">
                <option value="">Semua Status</option>
                <option value="hadir" @selected(request('status')==='hadir')>Hadir</option>
                <option value="izin" @selected(request('status')==='izin')>Izin</option>
                <option value="sakit" @selected(request('status')==='sakit')>Sakit</option>
                <option value="alpha" @selected(request('status')==='alpha')>Alpha</option>
            </select>
            <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Mapel</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td>{{ $attendance->student->user->name }}</td>
                            <td>{{ $attendance->schedule->classRoom->name }}</td>
                            <td>{{ $attendance->schedule->course->subject->name }}</td>
                            <td><span class="badge badge-status-{{ $attendance->status }}">{{ ucfirst($attendance->status) }}</span></td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.attendances.edit', $attendance) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.attendances.destroy', $attendance) }}" method="POST" class="d-inline" data-confirm-delete="Hapus presensi ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-clipboard-check"></i><p>Belum ada data presensi.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attendances->links() }}
    </div>
</div>
@endsection
