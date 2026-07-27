@extends('layouts.app')
@section('title', $material->title)
@section('page-title', $material->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('siswa.materials.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <h5>{{ $material->title }}</h5>
        <p class="text-muted small">{{ $material->course->subject->name }} &mdash; {{ $material->teacher->user->name }}</p>
        <p>{{ $material->description }}</p>

        @if($material->type === 'video' && $material->file_path)
            <video controls class="w-100 rounded" style="max-height:480px;">
                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($material->file_path) }}">
            </video>
        @elseif($material->type === 'link' && $material->video_url)
            <div class="ratio ratio-16x9">
                <iframe src="{{ $material->video_url }}" allowfullscreen></iframe>
            </div>
        @elseif($material->type === 'image' && $material->file_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($material->file_path) }}" class="img-fluid rounded">
        @elseif($material->file_path)
            <p><a href="{{ route('siswa.materials.download', $material) }}" class="btn btn-brand"><i class="fa-solid fa-download me-1"></i>Download {{ $material->file_name }}</a></p>
        @endif
    </div>
</div>
@endsection
