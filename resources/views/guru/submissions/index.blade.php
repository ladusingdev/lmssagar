@extends('layouts.app')
@section('title', 'Pengumpulan Tugas')
@section('page-title', 'Pengumpulan: ' . $assignment->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('guru.assignments.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <p class="text-muted">{{ $submissions->count() }} dari {{ $enrolledCount }} siswa telah mengumpulkan.</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Siswa</th><th>Waktu Kumpul</th><th>File</th><th>Status</th><th>Nilai</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $submission->student->user->name }}</td>
                            <td>{{ $submission->submitted_at->format('d M Y H:i') }}</td>
                            <td>
                                @if($submission->file_path)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($submission->file_path) }}" target="_blank"><i class="fa-solid fa-paperclip me-1"></i>{{ $submission->file_name }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php($badge = ['submitted'=>'bg-primary','late'=>'bg-warning text-dark','graded'=>'bg-success'][$submission->status] ?? 'bg-secondary')
                                <span class="badge {{ $badge }}">{{ ucfirst($submission->status) }}</span>
                            </td>
                            <td>{{ $submission->score ?? '-' }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('guru.submissions.grade', $submission) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i> Nilai</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Belum ada siswa yang mengumpulkan tugas.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
