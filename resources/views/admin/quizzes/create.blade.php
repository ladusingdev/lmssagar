@extends('layouts.app')
@section('title', 'Tambah Kuis')
@section('page-title', 'Tambah Kuis')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.quizzes.store') }}">
        @csrf
        @include('admin.quizzes._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan &amp; Kelola Soal</button>
        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
