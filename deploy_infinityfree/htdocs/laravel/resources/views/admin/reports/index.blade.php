@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
@php
$reports = [
    ['title' => 'Data Guru', 'icon' => 'chalkboard-user', 'key' => 'teachers'],
    ['title' => 'Data Siswa', 'icon' => 'user-graduate', 'key' => 'students'],
    ['title' => 'Data Nilai', 'icon' => 'chart-simple', 'key' => 'grades'],
    ['title' => 'Data Presensi', 'icon' => 'clipboard-check', 'key' => 'attendances'],
    ['title' => 'Data Materi', 'icon' => 'file-lines', 'key' => 'materials'],
    ['title' => 'Data Tugas', 'icon' => 'pen-to-square', 'key' => 'assignments'],
    ['title' => 'Data Ujian', 'icon' => 'file-shield', 'key' => 'exams'],
];
@endphp
<div class="row g-3">
    @foreach($reports as $report)
        <div class="col-md-4">
            <div class="card fade-in h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-{{ $report['icon'] }} fs-1 text-navy mb-3" style="color:#0f172a;"></i>
                    <h6>{{ $report['title'] }}</h6>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="{{ route('admin.reports.'.$report['key'].'.pdf') }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-file-pdf me-1"></i>PDF</a>
                        <a href="{{ route('admin.reports.'.$report['key'].'.excel') }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-file-excel me-1"></i>Excel</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
