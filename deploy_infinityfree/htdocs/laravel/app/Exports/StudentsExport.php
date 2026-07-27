<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with(['user', 'classRoom', 'department'])->get();
    }

    public function headings(): array
    {
        return ['Nama', 'NISN', 'NIS', 'Kelas', 'Jurusan', 'Email', 'Status'];
    }

    public function map($student): array
    {
        return [
            $student->user->name,
            $student->nisn ?? '-',
            $student->nis ?? '-',
            $student->classRoom->name ?? '-',
            $student->department->name ?? '-',
            $student->user->email,
            ucfirst($student->status),
        ];
    }
}
