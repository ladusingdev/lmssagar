@extends('layouts.app')
@section('title', 'Edit Tahun Ajaran')
@section('page-title', 'Edit Tahun Ajaran')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.academic-years.update', $academicYear) }}">
        @csrf @method('PUT')
        @include('admin.academic-years._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
