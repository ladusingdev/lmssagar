@extends('layouts.app')
@section('title', 'Input Presensi')
@section('page-title', 'Input Presensi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-5">
                <label class="form-label">Jadwal / Kelas</label>
                <select name="schedule_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Pilih Jadwal --</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}" @selected(($selectedSchedule?->id) == $schedule->id)>{{ $schedule->day_of_week }} - {{ $schedule->course->subject->name }} ({{ $schedule->classRoom->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
            </div>
        </form>

        @if($selectedSchedule)
            <form method="POST" action="{{ route('guru.attendances.store') }}">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light"><tr><th>Nama Siswa</th><th>Status</th><th>Catatan</th></tr></thead>
                        <tbody>
                            @foreach($students as $student)
                                @php($current = $existing->get($student->id))
                                <tr>
                                    <td>{{ $student->user->name }}</td>
                                    <td>
                                        <select name="status[{{ $student->id }}]" class="form-select form-select-sm">
                                            @foreach(['hadir'=>'Hadir','izin'=>'Izin','sakit'=>'Sakit','alpha'=>'Alpha'] as $val => $label)
                                                <option value="{{ $val }}" @selected(($current->status ?? 'hadir') === $val)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="note[{{ $student->id }}]" value="{{ $current->note ?? '' }}" class="form-control form-control-sm"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-brand"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Presensi</button>
            </form>
        @else
            <div class="empty-state"><i class="fa-solid fa-clipboard-check"></i><p>Pilih jadwal untuk mulai mengisi presensi.</p></div>
        @endif
    </div>
</div>
@endsection
