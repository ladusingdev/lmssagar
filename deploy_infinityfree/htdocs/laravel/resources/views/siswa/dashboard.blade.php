@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Siswa')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="stat-card bg-navy"><div class="stat-icon mb-2"><i class="fa-solid fa-book-open"></i></div><div class="stat-value">{{ $activeCourses }}</div><div class="stat-label">Mata Pelajaran Aktif</div></div></div>
    <div class="col-md-4"><div class="stat-card bg-orange"><div class="stat-icon mb-2"><i class="fa-solid fa-pen-to-square"></i></div><div class="stat-value">{{ $pendingAssignments->count() }}</div><div class="stat-label">Tugas Belum Dikerjakan</div></div></div>
    <div class="col-md-4"><div class="stat-card bg-green"><div class="stat-icon mb-2"><i class="fa-solid fa-clipboard-check"></i></div><div class="stat-value">{{ $attendancePercentage }}%</div><div class="stat-label">Persentase Kehadiran</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="section-card fade-in mb-3">
            <h6 class="mb-3">Jadwal Hari Ini</h6>
            @forelse($todaySchedules as $schedule)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <strong>{{ $schedule->course->subject->name }}</strong>
                        <div class="small text-muted">{{ $schedule->course->teacher->user->name }}</div>
                    </div>
                    <div class="text-end small text-muted">{{ substr($schedule->start_time,0,5) }} - {{ substr($schedule->end_time,0,5) }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">Tidak ada jadwal hari ini.</p>
            @endforelse
        </div>
        <div class="section-card fade-in">
            <h6 class="mb-3">Tugas Belum Dikerjakan</h6>
            @forelse($pendingAssignments as $assignment)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <div class="small fw-semibold">{{ $assignment->title }}</div>
                        <div class="small text-muted">{{ $assignment->course->subject->name }}</div>
                    </div>
                    <a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-sm btn-outline-primary">Kerjakan</a>
                </div>
            @empty
                <p class="text-muted small mb-0">Tidak ada tugas tertunda. Kerja bagus!</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-7">
        <div class="section-card fade-in mb-3">
            <h6 class="mb-3">Nilai Terbaru</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Mapel</th><th>Nilai Akhir</th><th>Huruf</th></tr></thead>
                    <tbody>
                        @forelse($latestGrades as $grade)
                            <tr>
                                <td>{{ $grade->course->subject->name }}</td>
                                <td>{{ $grade->final_score ? number_format($grade->final_score,1) : '-' }}</td>
                                <td><span class="badge bg-navy">{{ $grade->letter_grade ?? '-' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted small">Belum ada nilai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="section-card fade-in">
            <h6 class="mb-3">Pengumuman Terbaru</h6>
            @forelse($latestAnnouncements as $announcement)
                <div class="border-bottom py-2">
                    <div class="small fw-semibold">{{ $announcement->title }}</div>
                    <div class="small text-muted">{{ $announcement->published_at?->diffForHumans() }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">Tidak ada pengumuman terbaru.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
