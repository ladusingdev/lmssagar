@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-3 col-6"><div class="stat-card bg-navy"><div class="stat-icon mb-2"><i class="fa-solid fa-chalkboard-user"></i></div><div class="stat-value">{{ $stats['teachers'] }}</div><div class="stat-label">Total Guru</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card bg-orange"><div class="stat-icon mb-2"><i class="fa-solid fa-user-graduate"></i></div><div class="stat-value">{{ $stats['students'] }}</div><div class="stat-label">Total Siswa</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card bg-blue"><div class="stat-icon mb-2"><i class="fa-solid fa-school"></i></div><div class="stat-value">{{ $stats['classes'] }}</div><div class="stat-label">Total Kelas</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card bg-green"><div class="stat-icon mb-2"><i class="fa-solid fa-book"></i></div><div class="stat-value">{{ $stats['subjects'] }}</div><div class="stat-label">Mata Pelajaran</div></div></div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3 col-6"><div class="stat-card bg-purple"><div class="stat-icon mb-2"><i class="fa-solid fa-file-lines"></i></div><div class="stat-value">{{ $stats['materials'] }}</div><div class="stat-label">Total Materi</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card bg-navy"><div class="stat-icon mb-2"><i class="fa-solid fa-pen-to-square"></i></div><div class="stat-value">{{ $stats['assignments'] }}</div><div class="stat-label">Total Tugas</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card bg-red"><div class="stat-icon mb-2"><i class="fa-solid fa-file-shield"></i></div><div class="stat-value">{{ $stats['exams'] }}</div><div class="stat-label">Total Ujian</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card bg-orange"><div class="stat-icon mb-2"><i class="fa-solid fa-chart-simple"></i></div><div class="stat-value">{{ $gradeAverage }}</div><div class="stat-label">Rata-rata Nilai</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="section-card fade-in mb-3">
            <h6 class="mb-3">Grafik Aktivitas (7 Hari Terakhir)</h6>
            <canvas id="activityChart" height="110"></canvas>
        </div>
        <div class="section-card fade-in">
            <h6 class="mb-3">Statistik Presensi</h6>
            <canvas id="attendanceChart" height="110"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="section-card fade-in">
            <h6 class="mb-3">Aktivitas Terbaru</h6>
            @forelse($recentActivities as $activity)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <div class="small fw-semibold">{{ $activity->description }}</div>
                        <div class="small text-muted">{{ $activity->user->name ?? 'System' }}</div>
                    </div>
                    <div class="small text-muted">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('activityChart'), {
    type: 'line',
    data: {
        labels: @json($activityLabels),
        datasets: [{
            label: 'Aktivitas',
            data: @json($activityData),
            borderColor: '#f97316',
            backgroundColor: 'rgba(249,115,22,.15)',
            fill: true,
            tension: 0.35,
        }],
    },
    options: { plugins: { legend: { display: false } } },
});

new Chart(document.getElementById('attendanceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
        datasets: [{
            data: [
                {{ $attendanceRecap['hadir'] ?? 0 }},
                {{ $attendanceRecap['izin'] ?? 0 }},
                {{ $attendanceRecap['sakit'] ?? 0 }},
                {{ $attendanceRecap['alpha'] ?? 0 }},
            ],
            backgroundColor: ['#16a34a', '#2563eb', '#f59e0b', '#dc2626'],
        }],
    },
});
</script>
@endpush
