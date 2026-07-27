@extends('admin.reports.pdf.layout')
@section('title', 'Laporan Data Siswa')
@section('body')
<table>
    <thead><tr><th>No</th><th>Nama</th><th>NISN</th><th>Kelas</th><th>Jurusan</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($students as $i => $student)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $student->user->name }}</td>
                <td>{{ $student->nisn ?? '-' }}</td>
                <td>{{ $student->classRoom->name ?? '-' }}</td>
                <td>{{ $student->department->name ?? '-' }}</td>
                <td>{{ ucfirst($student->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
