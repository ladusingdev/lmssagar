<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GradesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Grade::with(['student.user', 'course.subject', 'course.classRoom'])->get();
    }

    public function headings(): array
    {
        return ['Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Nilai Tugas', 'Nilai Kuis', 'Nilai Ujian', 'Nilai Akhir', 'Huruf'];
    }

    public function map($grade): array
    {
        return [
            $grade->student->user->name,
            $grade->course->classRoom->name,
            $grade->course->subject->name,
            $grade->assignment_score ?? '-',
            $grade->quiz_score ?? '-',
            $grade->exam_score ?? '-',
            $grade->final_score ?? '-',
            $grade->letter_grade ?? '-',
        ];
    }
}
