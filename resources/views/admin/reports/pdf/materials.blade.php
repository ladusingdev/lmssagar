@extends('admin.reports.pdf.layout')
@section('title', 'Laporan Materi Pembelajaran')
@section('body')
<table>
    <thead><tr><th>No</th><th>Judul</th><th>Mapel</th><th>Kelas</th><th>Guru</th><th>Tipe</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($materials as $i => $material)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $material->title }}</td>
                <td>{{ $material->course->subject->name }}</td>
                <td>{{ $material->course->classRoom->name }}</td>
                <td>{{ $material->teacher->user->name }}</td>
                <td>{{ strtoupper($material->type) }}</td>
                <td>{{ $material->is_published ? 'Publish' : 'Draft' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
