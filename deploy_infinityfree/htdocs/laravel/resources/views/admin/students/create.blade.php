@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.students.store') }}">
        @csrf
        @include('admin.students._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
