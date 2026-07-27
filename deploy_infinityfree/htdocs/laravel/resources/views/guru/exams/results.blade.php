@extends('layouts.app')
@section('title', 'Hasil Ujian')
@section('page-title', 'Hasil Ujian: ' . $exam->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('guru.exams.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Siswa</th><th>Waktu Submit</th><th>Skor</th><th>Kelulusan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>{{ $attempt->student->user->name }}</td>
                            <td>{{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>{{ $attempt->score ?? '-' }} / {{ $exam->totalScore() }}</td>
                            <td>
                                @if($attempt->is_passed === null) - @elseif($attempt->is_passed)<span class="badge bg-success">Lulus</span>@else<span class="badge bg-danger">Tidak Lulus</span>@endif
                            </td>
                            <td><span class="badge bg-secondary">{{ ucfirst($attempt->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('guru.exams.attempts.review', [$exam, $attempt]) }}" class="btn btn-sm btn-outline-primary">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-chart-bar"></i><p>Belum ada siswa yang mengerjakan.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
