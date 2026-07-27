@extends('admin.reports.pdf.layout')
@section('title', 'Laporan Ujian Online')
@section('body')
<table>
    <thead><tr><th>No</th><th>Judul</th><th>Mapel</th><th>Kelas</th><th>Waktu Mulai</th><th>Peserta</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($exams as $i => $exam)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $exam->title }}</td>
                <td>{{ $exam->course->subject->name }}</td>
                <td>{{ $exam->course->classRoom->name }}</td>
                <td>{{ $exam->start_time->format('d-m-Y H:i') }}</td>
                <td>{{ $exam->attempts_count }}</td>
                <td>{{ $exam->is_published ? 'Publish' : 'Draft' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
