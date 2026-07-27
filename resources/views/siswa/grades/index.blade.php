@extends('layouts.app')
@section('title', 'Nilai')
@section('page-title', 'Nilai Saya')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Mata Pelajaran</th><th>Tugas</th><th>Kuis</th><th>Ujian</th><th>Nilai Akhir</th><th>Huruf</th></tr></thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr>
                            <td>{{ $grade->course->subject->name }}</td>
                            <td>{{ $grade->assignment_score ? number_format($grade->assignment_score,1) : '-' }}</td>
                            <td>{{ $grade->quiz_score ? number_format($grade->quiz_score,1) : '-' }}</td>
                            <td>{{ $grade->exam_score ? number_format($grade->exam_score,1) : '-' }}</td>
                            <td class="fw-semibold">{{ $grade->final_score ? number_format($grade->final_score,1) : '-' }}</td>
                            <td><span class="badge bg-navy">{{ $grade->letter_grade ?? '-' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-chart-simple"></i><p>Belum ada data nilai.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card fade-in mt-3">
    <div class="card-body">
        <h6 class="mb-3">Grafik Nilai Akhir</h6>
        <canvas id="gradeChart" height="90"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('gradeChart'), {
    type: 'bar',
    data: {
        labels: @json($grades->pluck('course.subject.name')),
        datasets: [{
            label: 'Nilai Akhir',
            data: @json($grades->pluck('final_score')),
            backgroundColor: '#f97316',
        }],
    },
    options: { scales: { y: { beginAtZero: true, max: 100 } } },
});
</script>
@endpush
