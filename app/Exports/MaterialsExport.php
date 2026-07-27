<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaterialsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Material::with(['course.subject', 'course.classRoom', 'teacher.user'])->get();
    }

    public function headings(): array
    {
        return ['Judul', 'Mata Pelajaran', 'Kelas', 'Guru', 'Tipe', 'Status'];
    }

    public function map($material): array
    {
        return [
            $material->title,
            $material->course->subject->name,
            $material->course->classRoom->name,
            $material->teacher->user->name,
            strtoupper($material->type),
            $material->is_published ? 'Publish' : 'Draft',
        ];
    }
}
