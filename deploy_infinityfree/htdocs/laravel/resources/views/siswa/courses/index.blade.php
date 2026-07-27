@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')
@section('content')
<div class="row g-3">
    @forelse($courses as $course)
        <div class="col-md-4">
            <div class="card fade-in h-100">
                <div class="card-body">
                    <h6>{{ $course->subject->name }}</h6>
                    <p class="text-muted small mb-1"><i class="fa-solid fa-chalkboard-user me-1"></i>{{ $course->teacher->user->name }}</p>
                    <p class="text-muted small mb-0"><i class="fa-solid fa-school me-1"></i>{{ $course->classRoom->name }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="empty-state"><i class="fa-solid fa-book-open"></i><p>Anda belum terdaftar pada mata pelajaran apapun.</p></div></div>
    @endforelse
</div>
@endsection
