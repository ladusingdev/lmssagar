@extends('layouts.app')

@section('title', 'Tambah Guru')
@section('page-title', 'Tambah Guru')

@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.teachers.store') }}">
            @csrf
            @include('admin.teachers._form')
            <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
