@extends('layouts.app')
@section('title', 'Edit Jadwal')
@section('page-title', 'Edit Jadwal')
@section('content')
<div class="card fade-in"><div class="card-body">
    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
        @csrf @method('PUT')
        @include('admin.schedules._form')
        <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
