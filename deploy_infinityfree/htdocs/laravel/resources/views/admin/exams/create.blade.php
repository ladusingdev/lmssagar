@extends('layouts.app')
@section('title', 'Tambah Ujian')
@section('page-title', 'Tambah Ujian Online')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.exams.store') }}">
        @csrf
        @include('admin.exams._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan &amp; Kelola Soal</button>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
