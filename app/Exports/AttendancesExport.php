<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Attendance::with(['student.user', 'schedule.classRoom', 'schedule.course.subject'])->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Status', 'Catatan'];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date->format('d-m-Y'),
            $attendance->student->user->name,
            $attendance->schedule->classRoom->name,
            $attendance->schedule->course->subject->name,
            ucfirst($attendance->status),
            $attendance->note ?? '-',
        ];
    }
}
