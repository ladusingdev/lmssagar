@extends('layouts.app')
@section('title', 'Edit Tugas')
@section('page-title', 'Edit Tugas')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.assignments.update', $assignment) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('guru.assignments._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.assignments.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
