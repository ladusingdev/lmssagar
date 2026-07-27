@extends('layouts.app')
@section('title', 'Ujian Online')
@section('page-title', 'Ujian Online')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel</th><th>Waktu</th><th>Soal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td>{{ $exam->title }}</td>
                            <td>{{ $exam->course->subject->name }}</td>
                            <td>{{ $exam->start_time->format('d/m/Y H:i') }} - {{ $exam->end_time->format('H:i') }}</td>
                            <td>{{ $exam->questions_count }}</td>
                            <td>
                                @php($status = $attemptStatus[$exam->id] ?? null)
                                @if($status === 'in_progress')<span class="badge bg-warning text-dark">Sedang Dikerjakan</span>
                                @elseif(in_array($status, ['submitted','graded']))<span class="badge bg-success">Selesai</span>
                                @elseif(!$exam->isOpen() && now()->lt($exam->start_time))<span class="badge bg-secondary">Belum Dibuka</span>
                                @elseif(!$exam->isOpen())<span class="badge bg-danger">Ditutup</span>
                                @else<span class="badge bg-primary">Tersedia</span>
                                @endif
                            </td>
                            <td class="text-end"><a href="{{ route('siswa.exams.show', $exam) }}" class="btn btn-sm btn-brand">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-file-shield"></i><p>Belum ada ujian.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $exams->links() }}
    </div>
</div>
@endsection
