@extends('layouts.app')
@section('title', 'Edit Kuis')
@section('page-title', 'Edit Kuis')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.quizzes.update', $quiz) }}">
        @csrf @method('PUT')
        @include('guru.quizzes._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.quizzes.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
