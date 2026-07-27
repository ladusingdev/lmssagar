@extends('layouts.app')
@section('title', 'Tambah Soal')
@section('page-title', 'Tambah Soal Kuis')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.quizzes.questions.store', $quiz) }}">
        @csrf
        @include('admin.quizzes.questions._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
