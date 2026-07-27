<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeachersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Teacher::with('user')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'NIP', 'NUPTK', 'Email', 'No. Telepon', 'Status Kepegawaian', 'Status Akun'];
    }

    public function map($teacher): array
    {
        return [
            $teacher->user->name,
            $teacher->nip ?? '-',
            $teacher->nuptk ?? '-',
            $teacher->user->email,
            $teacher->user->phone ?? '-',
            $teacher->employment_status,
            $teacher->user->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }
}
