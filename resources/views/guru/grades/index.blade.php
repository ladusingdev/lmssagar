@extends('layouts.app')
@section('title', 'Nilai')
@section('page-title', 'Rekap Nilai')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form class="d-flex gap-2 flex-wrap mb-3" method="GET">
            <select name="course_id" class="form-select form-select-sm" style="width:260px;">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->subject->name }} - {{ $course->classRoom->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            @if(request('course_id'))
                <form action="{{ route('guru.grades.recalculate', request('course_id')) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-rotate me-1"></i>Hitung Ulang Nilai</button>
                </form>
            @endif
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Siswa</th><th>Mapel</th><th>Kelas</th><th>Tugas</th><th>Kuis</th><th>Ujian</th><th>Akhir</th><th>Huruf</th></tr></thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr>
                            <td>{{ $grade->student->user->name }}</td>
                            <td>{{ $grade->course->subject->name }}</td>
                            <td>{{ $grade->course->classRoom->name }}</td>
                            <td>{{ $grade->assignment_score ? number_format($grade->assignment_score,1) : '-' }}</td>
                            <td>{{ $grade->quiz_score ? number_format($grade->quiz_score,1) : '-' }}</td>
                            <td>{{ $grade->exam_score ? number_format($grade->exam_score,1) : '-' }}</td>
                            <td class="fw-semibold">{{ $grade->final_score ? number_format($grade->final_score,1) : '-' }}</td>
                            <td><span class="badge bg-navy">{{ $grade->letter_grade ?? '-' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="empty-state"><i class="fa-solid fa-chart-simple"></i><p>Belum ada data nilai.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $grades->links() }}
    </div>
</div>
@endsection
