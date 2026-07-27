@extends('layouts.app')
@section('title', 'Tambah Materi')
@section('page-title', 'Tambah Materi')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.materials.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.materials._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
