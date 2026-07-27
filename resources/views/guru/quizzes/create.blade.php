@extends('layouts.app')
@section('title', 'Buat Kuis')
@section('page-title', 'Buat Kuis')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.quizzes.store') }}">
        @csrf
        @include('guru.quizzes._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan &amp; Kelola Soal</button>
        <a href="{{ route('guru.quizzes.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
