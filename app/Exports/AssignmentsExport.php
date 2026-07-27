<?php

namespace App\Exports;

use App\Models\Assignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssignmentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Assignment::with(['course.subject', 'course.classRoom'])->withCount('submissions')->get();
    }

    public function headings(): array
    {
        return ['Judul', 'Mata Pelajaran', 'Kelas', 'Deadline', 'Jumlah Pengumpulan', 'Status'];
    }

    public function map($assignment): array
    {
        return [
            $assignment->title,
            $assignment->course->subject->name,
            $assignment->course->classRoom->name,
            $assignment->deadline->format('d-m-Y H:i'),
            $assignment->submissions_count,
            $assignment->is_published ? 'Publish' : 'Draft',
        ];
    }
}
