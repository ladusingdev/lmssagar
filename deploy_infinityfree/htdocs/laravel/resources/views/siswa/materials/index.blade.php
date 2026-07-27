@extends('layouts.app')
@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form class="d-flex gap-2 mb-3" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari materi" style="width:220px;">
            <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <div class="row g-3">
            @forelse($materials as $material)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2">{{ strtoupper($material->type) }}</span>
                            <h6>{{ $material->title }}</h6>
                            <p class="text-muted small">{{ $material->course->subject->name }} &mdash; {{ $material->teacher->user->name }}</p>
                            <a href="{{ route('siswa.materials.show', $material) }}" class="btn btn-sm btn-brand">Lihat Materi</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-state"><i class="fa-solid fa-file-lines"></i><p>Belum ada materi tersedia.</p></div></div>
            @endforelse
        </div>
        {{ $materials->links() }}
    </div>
</div>
@endsection
