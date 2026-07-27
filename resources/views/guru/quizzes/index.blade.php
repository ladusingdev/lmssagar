@extends('layouts.app')
@section('title', 'Manajemen Kuis')
@section('page-title', 'Kuis')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('guru.quizzes.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Buat Kuis</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel / Kelas</th><th>Waktu</th><th>Soal</th><th>Peserta</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td>{{ $quiz->title }}</td>
                            <td>{{ $quiz->course->subject->name }} - {{ $quiz->course->classRoom->name }}</td>
                            <td>{{ $quiz->start_time->format('d/m/Y H:i') }}</td>
                            <td>{{ $quiz->questions_count }}</td>
                            <td>{{ $quiz->attempts_count }}</td>
                            <td>{!! $quiz->is_published ? '<span class="badge bg-success">Publish</span>' : '<span class="badge bg-warning text-dark">Draft</span>' !!}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('guru.quizzes.questions.index', $quiz) }}" class="btn btn-sm btn-outline-secondary" title="Soal"><i class="fa-solid fa-list-check"></i></a>
                                <a href="{{ route('guru.quizzes.results', $quiz) }}" class="btn btn-sm btn-outline-secondary" title="Hasil"><i class="fa-solid fa-chart-bar"></i></a>
                                <a href="{{ route('guru.quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('guru.quizzes.destroy', $quiz) }}" method="POST" class="d-inline" data-confirm-delete="Hapus kuis ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-list-check"></i><p>Belum ada kuis.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
