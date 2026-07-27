@extends('layouts.app')
@section('title', 'Manajemen Nilai')
@section('page-title', 'Manajemen Nilai')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form class="d-flex gap-2 flex-wrap mb-3" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama siswa" style="width:200px;">
            <select name="class_id" class="form-select form-select-sm" style="width:180px;">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
            <select name="course_id" class="form-select form-select-sm" style="width:220px;">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->subject->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Siswa</th><th>Mapel</th><th>Kelas</th><th>Tugas</th><th>Kuis</th><th>Ujian</th><th>Nilai Akhir</th><th>Huruf</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr>
                            <td>{{ $grade->student->user->name }}</td>
                            <td>{{ $grade->course->subject->name }}</td>
                            <td>{{ $grade->course->classRoom->name }}</td>
                            <td>{{ $grade->assignment_score ? number_format($grade->assignment_score, 1) : '-' }}</td>
                            <td>{{ $grade->quiz_score ? number_format($grade->quiz_score, 1) : '-' }}</td>
                            <td>{{ $grade->exam_score ? number_format($grade->exam_score, 1) : '-' }}</td>
                            <td class="fw-semibold">{{ $grade->final_score ? number_format($grade->final_score, 1) : '-' }}</td>
                            <td><span class="badge bg-navy">{{ $grade->letter_grade ?? '-' }}</span></td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.grades.edit', $grade) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9"><div class="empty-state"><i class="fa-solid fa-chart-simple"></i><p>Belum ada data nilai. Nilai akan muncul otomatis setelah tugas/kuis/ujian dinilai.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $grades->links() }}
    </div>
</div>
@endsection
