@extends('layouts.app')
@section('title', 'Tambah Jurusan')
@section('page-title', 'Tambah Jurusan')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.departments.store') }}">
        @csrf
        @include('admin.departments._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
