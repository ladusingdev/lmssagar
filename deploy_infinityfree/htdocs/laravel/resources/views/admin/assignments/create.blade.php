@extends('layouts.app')
@section('title', 'Tambah Tugas')
@section('page-title', 'Tambah Tugas')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.assignments.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.assignments._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
