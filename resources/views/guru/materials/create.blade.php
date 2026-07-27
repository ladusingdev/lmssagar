@extends('layouts.app')
@section('title', 'Tambah Materi')
@section('page-title', 'Tambah Materi')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.materials.store') }}" enctype="multipart/form-data">
        @csrf
        @include('guru.materials._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.materials.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
