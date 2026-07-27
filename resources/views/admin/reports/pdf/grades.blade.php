@extends('admin.reports.pdf.layout')
@section('title', 'Laporan Nilai')
@section('body')
<table>
    <thead><tr><th>No</th><th>Nama Siswa</th><th>Kelas</th><th>Mapel</th><th>Tugas</th><th>Kuis</th><th>Ujian</th><th>Akhir</th><th>Huruf</th></tr></thead>
    <tbody>
        @foreach($grades as $i => $grade)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $grade->student->user->name }}</td>
                <td>{{ $grade->course->classRoom->name }}</td>
                <td>{{ $grade->course->subject->name }}</td>
                <td>{{ $grade->assignment_score ?? '-' }}</td>
                <td>{{ $grade->quiz_score ?? '-' }}</td>
                <td>{{ $grade->exam_score ?? '-' }}</td>
                <td>{{ $grade->final_score ?? '-' }}</td>
                <td>{{ $grade->letter_grade ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
