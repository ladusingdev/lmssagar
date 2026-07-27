@extends('layouts.app')
@section('title', 'Mata Pelajaran Saya')
@section('page-title', 'Mata Pelajaran Saya')
@section('content')
<div class="row g-3">
    @forelse($courses as $course)
        <div class="col-md-4">
            <div class="card fade-in h-100">
                <div class="card-body">
                    <h6>{{ $course->subject->name }}</h6>
                    <p class="text-muted small mb-2">{{ $course->classRoom->name }} &mdash; {{ $course->academicYear->name }} ({{ $course->academicYear->semester }})</p>
                    <p class="small mb-3"><i class="fa-solid fa-user-graduate me-1"></i>{{ $course->enrollments_count }} siswa</p>
                    <a href="{{ route('guru.courses.show', $course) }}" class="btn btn-sm btn-brand">Kelola Kelas</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="empty-state"><i class="fa-solid fa-book-open"></i><p>Anda belum memiliki penugasan mengajar.</p></div></div>
    @endforelse
</div>
<div class="mt-3">{{ $courses->links() }}</div>
@endsection
