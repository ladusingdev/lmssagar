@extends('layouts.app')
@section('title', 'Tambah Mapel')
@section('page-title', 'Tambah Mata Pelajaran')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.subjects.store') }}">
        @csrf
        @include('admin.subjects._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
