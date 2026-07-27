@extends('admin.reports.pdf.layout')
@section('title', 'Laporan Data Guru')
@section('body')
<table>
    <thead><tr><th>No</th><th>Nama</th><th>NIP</th><th>Email</th><th>Status Kepegawaian</th><th>Status Akun</th></tr></thead>
    <tbody>
        @foreach($teachers as $i => $teacher)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $teacher->user->name }}</td>
                <td>{{ $teacher->nip ?? '-' }}</td>
                <td>{{ $teacher->user->email }}</td>
                <td>{{ $teacher->employment_status }}</td>
                <td>{{ $teacher->user->is_active ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
