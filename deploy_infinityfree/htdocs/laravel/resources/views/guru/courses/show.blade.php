@extends('layouts.app')
@section('title', $course->subject->name)
@section('page-title', $course->subject->name . ' - ' . $course->classRoom->name)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <a href="{{ route('guru.courses.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <h5>Daftar Siswa ({{ $course->students->count() }})</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Nama</th><th>NISN</th><th>Email</th></tr></thead>
                <tbody>
                    @foreach($course->students as $student)
                        <tr>
                            <td class="d-flex align-items-center gap-2">
                                <img src="{{ $student->user->avatar_url }}" class="avatar-circle">
                                {{ $student->user->name }}
                            </td>
                            <td>{{ $student->nisn ?? '-' }}</td>
                            <td>{{ $student->user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
