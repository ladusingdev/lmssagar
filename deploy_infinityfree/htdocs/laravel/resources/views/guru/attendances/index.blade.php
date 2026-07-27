@extends('layouts.app')
@section('title', 'Presensi')
@section('page-title', 'Presensi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2" method="GET">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm" style="width:170px;">
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
            <a href="{{ route('guru.attendances.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Input Presensi</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Mapel</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td>{{ $attendance->student->user->name }}</td>
                            <td>{{ $attendance->schedule->classRoom->name }}</td>
                            <td>{{ $attendance->schedule->course->subject->name }}</td>
                            <td><span class="badge badge-status-{{ $attendance->status }}">{{ ucfirst($attendance->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-clipboard-check"></i><p>Belum ada data presensi.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attendances->links() }}
    </div>
</div>
@endsection
