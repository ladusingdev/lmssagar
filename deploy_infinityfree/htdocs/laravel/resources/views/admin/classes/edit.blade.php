@extends('layouts.app')
@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.classes.update', $classRoom) }}">
        @csrf @method('PUT')
        @include('admin.classes._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
