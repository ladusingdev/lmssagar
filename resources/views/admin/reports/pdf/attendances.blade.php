@extends('admin.reports.pdf.layout')
@section('title', 'Laporan Presensi')
@section('body')
<table>
    <thead><tr><th>No</th><th>Tanggal</th><th>Nama Siswa</th><th>Kelas</th><th>Mapel</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($attendances as $i => $attendance)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $attendance->date->format('d-m-Y') }}</td>
                <td>{{ $attendance->student->user->name }}</td>
                <td>{{ $attendance->schedule->classRoom->name }}</td>
                <td>{{ $attendance->schedule->course->subject->name }}</td>
                <td>{{ ucfirst($attendance->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
