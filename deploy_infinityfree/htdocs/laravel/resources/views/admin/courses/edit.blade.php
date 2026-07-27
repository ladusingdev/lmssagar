@extends('layouts.app')
@section('title', 'Edit Penugasan')
@section('page-title', 'Edit Penugasan Mengajar')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.courses.update', $course) }}">
        @csrf @method('PUT')
        @include('admin.courses._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
