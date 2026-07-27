@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
        @csrf @method('PUT')
        @include('admin.announcements._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
