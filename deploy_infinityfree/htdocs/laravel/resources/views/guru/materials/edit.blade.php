@extends('layouts.app')
@section('title', 'Edit Materi')
@section('page-title', 'Edit Materi')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.materials.update', $material) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('guru.materials._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.materials.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
