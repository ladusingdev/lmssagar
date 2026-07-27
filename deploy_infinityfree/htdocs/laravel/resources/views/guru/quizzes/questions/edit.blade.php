@extends('layouts.app')
@section('title', 'Edit Soal')
@section('page-title', 'Edit Soal Kuis')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.quizzes.questions.update', [$quiz, $question]) }}">
        @csrf @method('PUT')
        @include('guru.quizzes.questions._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.quizzes.questions.index', $quiz) }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
