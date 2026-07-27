@extends('layouts.app')
@section('title', 'Tambah Jadwal')
@section('page-title', 'Tambah Jadwal')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.schedules.store') }}">
        @csrf
        @include('admin.schedules._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
