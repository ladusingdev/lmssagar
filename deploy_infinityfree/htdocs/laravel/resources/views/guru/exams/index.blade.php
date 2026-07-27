@extends('layouts.app')
@section('title', 'Manajemen Ujian Online')
@section('page-title', 'Ujian Online')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('guru.exams.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Buat Ujian</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel / Kelas</th><th>Waktu</th><th>Soal</th><th>Peserta</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td>{{ $exam->title }}</td>
                            <td>{{ $exam->course->subject->name }} - {{ $exam->course->classRoom->name }}</td>
                            <td>{{ $exam->start_time->format('d/m/Y H:i') }}</td>
                            <td>{{ $exam->questions_count }}</td>
                            <td>{{ $exam->attempts_count }}</td>
                            <td>{!! $exam->is_published ? '<span class="badge bg-success">Publish</span>' : '<span class="badge bg-warning text-dark">Draft</span>' !!}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-sm btn-outline-secondary" title="Soal"><i class="fa-solid fa-list-check"></i></a>
                                <a href="{{ route('guru.exams.results', $exam) }}" class="btn btn-sm btn-outline-secondary" title="Hasil"><i class="fa-solid fa-chart-bar"></i></a>
                                <a href="{{ route('guru.exams.edit', $exam) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('guru.exams.destroy', $exam) }}" method="POST" class="d-inline" data-confirm-delete="Hapus ujian ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-file-shield"></i><p>Belum ada ujian.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $exams->links() }}
    </div>
</div>
@endsection
