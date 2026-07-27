@extends('layouts.app')
@section('title', 'Edit Soal')
@section('page-title', 'Edit Soal Ujian')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.exams.questions.update', [$exam, $question]) }}">
        @csrf @method('PUT')
        @include('admin.exams.questions._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.exams.questions.index', $exam) }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
