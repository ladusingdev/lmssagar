@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.students.update', $student) }}">
        @csrf @method('PUT')
        @include('admin.students._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
