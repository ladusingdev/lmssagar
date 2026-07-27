@extends('layouts.app')
@section('title', 'Jadwal Mengajar')
@section('page-title', 'Jadwal Mengajar')
@section('content')
@foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
    <div class="card fade-in mb-3">
        <div class="card-body">
            <h6 class="mb-3">{{ $day }}</h6>
            @forelse($schedules->get($day, collect()) as $schedule)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <strong>{{ $schedule->course->subject->name }}</strong>
                        <div class="small text-muted">{{ $schedule->classRoom->name }} @if($schedule->room) &mdash; Ruang {{ $schedule->room }} @endif</div>
                    </div>
                    <div class="text-end small text-muted">{{ substr($schedule->start_time,0,5) }} - {{ substr($schedule->end_time,0,5) }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">Tidak ada jadwal.</p>
            @endforelse
        </div>
    </div>
@endforeach
@endsection
