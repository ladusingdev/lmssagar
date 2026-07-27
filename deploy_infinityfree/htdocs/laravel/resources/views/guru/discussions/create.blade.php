@extends('layouts.app')
@section('title', 'Buat Thread')
@section('page-title', 'Buat Thread Diskusi')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.discussions.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Mata Pelajaran / Kelas</label>
            <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">
                <option value="">-- Pilih --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->subject->name }} - {{ $course->classRoom->name }}</option>
                @endforeach
            </select>
            @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="4">{{ old('body') }}</textarea>
            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Buat Thread</button>
        <a href="{{ route('guru.discussions.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
