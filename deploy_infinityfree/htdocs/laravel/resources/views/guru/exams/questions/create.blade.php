@extends('layouts.app')
@section('title', 'Tambah Soal')
@section('page-title', 'Tambah Soal Ujian')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.exams.questions.store', $exam) }}">
        @csrf
        @include('guru.exams.questions._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
