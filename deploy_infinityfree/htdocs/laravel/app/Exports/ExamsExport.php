<?php

namespace App\Exports;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Exam::with(['course.subject', 'course.classRoom'])->withCount('attempts')->get();
    }

    public function headings(): array
    {
        return ['Judul', 'Mata Pelajaran', 'Kelas', 'Waktu Mulai', 'Waktu Selesai', 'Jumlah Peserta', 'Status'];
    }

    public function map($exam): array
    {
        return [
            $exam->title,
            $exam->course->subject->name,
            $exam->course->classRoom->name,
            $exam->start_time->format('d-m-Y H:i'),
            $exam->end_time->format('d-m-Y H:i'),
            $exam->attempts_count,
            $exam->is_published ? 'Publish' : 'Draft',
        ];
    }
}
