@extends('layouts.app')
@section('title', 'Tugas')
@section('page-title', 'Tugas')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel</th><th>Deadline</th><th>Status</th><th>Nilai</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->title }}</td>
                            <td>{{ $assignment->course->subject->name }}</td>
                            <td>{{ $assignment->deadline->format('d M Y H:i') }}</td>
                            <td>
                                @if($assignment->submissions_count > 0)
                                    <span class="badge bg-success">Sudah Dikumpulkan</span>
                                @elseif($assignment->isPastDeadline())
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Dikerjakan</span>
                                @endif
                            </td>
                            <td>{{ $mySubmissions[$assignment->id] ?? '-' }}</td>
                            <td class="text-end"><a href="{{ route('siswa.assignments.show', $assignment) }}" class="btn btn-sm btn-brand">Kerjakan</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-pen-to-square"></i><p>Belum ada tugas.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $assignments->links() }}
    </div>
</div>
@endsection
