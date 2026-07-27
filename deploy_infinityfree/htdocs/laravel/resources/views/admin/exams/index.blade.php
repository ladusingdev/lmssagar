@extends('layouts.app')
@section('title', 'Manajemen Ujian Online')
@section('page-title', 'Manajemen Ujian Online')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex gap-2" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari judul ujian" style="width:220px;">
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <a href="{{ route('admin.exams.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Ujian</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel / Kelas</th><th>Waktu</th><th>Jml Soal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td>{{ $exam->title }}</td>
                            <td>{{ $exam->course->subject->name }} - {{ $exam->course->classRoom->name }}</td>
                            <td>{{ $exam->start_time->format('d/m/Y H:i') }} - {{ $exam->end_time->format('H:i') }}</td>
                            <td>{{ $exam->questions_count }}</td>
                            <td>{!! $exam->is_published ? '<span class="badge bg-success">Publish</span>' : '<span class="badge bg-warning text-dark">Draft</span>' !!}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.exams.questions.index', $exam) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-list-check"></i></a>
                                <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="d-inline" data-confirm-delete="Hapus ujian ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-file-shield"></i><p>Belum ada data ujian.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $exams->links() }}
    </div>
</div>
@endsection
