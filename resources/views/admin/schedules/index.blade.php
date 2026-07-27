@extends('layouts.app')
@section('title', 'Manajemen Jadwal')
@section('page-title', 'Manajemen Jadwal Pelajaran')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2" method="GET">
                <select name="class_id" class="form-select form-select-sm" style="width:180px;">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Jadwal</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Hari</th><th>Jam</th><th>Kelas</th><th>Mapel</th><th>Guru</th><th>Ruang</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->day_of_week }}</td>
                            <td>{{ substr($schedule->start_time,0,5) }} - {{ substr($schedule->end_time,0,5) }}</td>
                            <td>{{ $schedule->classRoom->name }}</td>
                            <td>{{ $schedule->course->subject->name }}</td>
                            <td>{{ $schedule->course->teacher->user->name }}</td>
                            <td>{{ $schedule->room ?? '-' }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="d-inline" data-confirm-delete="Hapus jadwal ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-clock"></i><p>Belum ada data jadwal.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $schedules->links() }}
    </div>
</div>
@endsection
