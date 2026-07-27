@extends('layouts.app')
@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('guru.announcements.store') }}">
        @csrf
        @include('guru.announcements._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('guru.announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
