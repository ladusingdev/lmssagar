@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Guru')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="stat-card bg-navy"><div class="stat-icon mb-2"><i class="fa-solid fa-file-lines"></i></div><div class="stat-value">{{ $stats['materials'] }}</div><div class="stat-label">Jumlah Materi</div></div></div>
    <div class="col-md-4"><div class="stat-card bg-orange"><div class="stat-icon mb-2"><i class="fa-solid fa-pen-to-square"></i></div><div class="stat-value">{{ $stats['assignments'] }}</div><div class="stat-label">Jumlah Tugas</div></div></div>
    <div class="col-md-4"><div class="stat-card bg-blue"><div class="stat-icon mb-2"><i class="fa-solid fa-user-graduate"></i></div><div class="stat-value">{{ $stats['students'] }}</div><div class="stat-label">Jumlah Siswa</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="section-card fade-in">
            <h6 class="mb-3">Jadwal Hari Ini</h6>
            @forelse($todaySchedules as $schedule)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <strong>{{ $schedule->course->subject->name }}</strong>
                        <div class="small text-muted">{{ $schedule->classRoom->name }}</div>
                    </div>
                    <div class="text-end small text-muted">{{ substr($schedule->start_time,0,5) }} - {{ substr($schedule->end_time,0,5) }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">Tidak ada jadwal hari ini.</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-7">
        <div class="section-card fade-in mb-3">
            <h6 class="mb-3">Grafik Pengumpulan Tugas (7 Hari Terakhir)</h6>
            <canvas id="activityChart" height="110"></canvas>
        </div>
        <div class="section-card fade-in">
            <h6 class="mb-3">Pengumpulan Tugas Terbaru</h6>
            @forelse($recentSubmissions as $submission)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <div class="small fw-semibold">{{ $submission->student->user->name }}</div>
                        <div class="small text-muted">{{ $submission->assignment->title }}</div>
                    </div>
                    <div class="small text-muted">{{ $submission->submitted_at->diffForHumans() }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada pengumpulan tugas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('activityChart'), {
    type: 'bar',
    data: {
        labels: @json($activityLabels),
        datasets: [{ label: 'Pengumpulan', data: @json($activityData), backgroundColor: '#f97316' }],
    },
    options: { plugins: { legend: { display: false } } },
});
</script>
@endpush
