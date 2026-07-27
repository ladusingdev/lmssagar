@extends('layouts.app')
@section('title', 'Kuis')
@section('page-title', 'Kuis')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel</th><th>Waktu</th><th>Soal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td>{{ $quiz->title }}</td>
                            <td>{{ $quiz->course->subject->name }}</td>
                            <td>{{ $quiz->start_time->format('d/m/Y H:i') }} - {{ $quiz->end_time->format('H:i') }}</td>
                            <td>{{ $quiz->questions_count }}</td>
                            <td>
                                @php($status = $attemptStatus[$quiz->id] ?? null)
                                @if($status === 'in_progress')<span class="badge bg-warning text-dark">Sedang Dikerjakan</span>
                                @elseif(in_array($status, ['submitted','graded']))<span class="badge bg-success">Selesai</span>
                                @elseif(!$quiz->isOpen() && now()->lt($quiz->start_time))<span class="badge bg-secondary">Belum Dibuka</span>
                                @elseif(!$quiz->isOpen())<span class="badge bg-danger">Ditutup</span>
                                @else<span class="badge bg-primary">Tersedia</span>
                                @endif
                            </td>
                            <td class="text-end"><a href="{{ route('siswa.quizzes.show', $quiz) }}" class="btn btn-sm btn-brand">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-list-check"></i><p>Belum ada kuis.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
