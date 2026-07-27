@extends('layouts.app')
@section('title', 'Presensi')
@section('page-title', 'Presensi Saya')
@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="stat-card bg-green"><div class="stat-value">{{ $recap['hadir'] ?? 0 }}</div><div class="stat-label">Hadir</div></div></div>
    <div class="col-md-3"><div class="stat-card bg-blue"><div class="stat-value">{{ $recap['izin'] ?? 0 }}</div><div class="stat-label">Izin</div></div></div>
    <div class="col-md-3"><div class="stat-card bg-orange"><div class="stat-value">{{ $recap['sakit'] ?? 0 }}</div><div class="stat-label">Sakit</div></div></div>
    <div class="col-md-3"><div class="stat-card bg-red"><div class="stat-value">{{ $recap['alpha'] ?? 0 }}</div><div class="stat-label">Alpha</div></div></div>
</div>
<div class="card fade-in mb-3">
    <div class="card-body">
        <strong>Persentase Kehadiran: {{ $percentageHadir }}%</strong>
        <div class="progress mt-2" style="height:10px;">
            <div class="progress-bar bg-success" style="width: {{ $percentageHadir }}%;"></div>
        </div>
    </div>
</div>
<div class="card fade-in">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Tanggal</th><th>Mata Pelajaran</th><th>Status</th><th>Catatan</th></tr></thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td>{{ $attendance->schedule->course->subject->name }}</td>
                            <td><span class="badge badge-status-{{ $attendance->status }}">{{ ucfirst($attendance->status) }}</span></td>
                            <td>{{ $attendance->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state"><i class="fa-solid fa-clipboard-check"></i><p>Belum ada data presensi.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attendances->links() }}
    </div>
</div>
@endsection
