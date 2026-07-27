@extends('layouts.app')
@section('title', 'Buat Ujian')
@section('page-title', 'Buat Ujian Online')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.exams.store') }}">
        @csrf
        @include('guru.exams._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan &amp; Kelola Soal</button>
        <a href="{{ route('guru.exams.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
