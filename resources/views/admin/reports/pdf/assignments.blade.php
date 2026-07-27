@extends('admin.reports.pdf.layout')
@section('title', 'Laporan Tugas')
@section('body')
<table>
    <thead><tr><th>No</th><th>Judul</th><th>Mapel</th><th>Kelas</th><th>Deadline</th><th>Pengumpulan</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($assignments as $i => $assignment)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $assignment->title }}</td>
                <td>{{ $assignment->course->subject->name }}</td>
                <td>{{ $assignment->course->classRoom->name }}</td>
                <td>{{ $assignment->deadline->format('d-m-Y H:i') }}</td>
                <td>{{ $assignment->submissions_count }}</td>
                <td>{{ $assignment->is_published ? 'Publish' : 'Draft' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
