<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AssignmentsExport;
use App\Exports\AttendancesExport;
use App\Exports\ExamsExport;
use App\Exports\GradesExport;
use App\Exports\MaterialsExport;
use App\Exports\StudentsExport;
use App\Exports\TeachersExport;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Material;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index');
    }

    public function teachersPdf(): Response
    {
        $teachers = Teacher::with('user')->get();

        return Pdf::loadView('admin.reports.pdf.teachers', compact('teachers'))->stream('laporan-guru.pdf');
    }

    public function teachersExcel(): BinaryFileResponse
    {
        return Excel::download(new TeachersExport, 'laporan-guru.xlsx');
    }

    public function studentsPdf(): Response
    {
        $students = Student::with(['user', 'classRoom', 'department'])->get();

        return Pdf::loadView('admin.reports.pdf.students', compact('students'))->stream('laporan-siswa.pdf');
    }

    public function studentsExcel(): BinaryFileResponse
    {
        return Excel::download(new StudentsExport, 'laporan-siswa.xlsx');
    }

    public function gradesPdf(): Response
    {
        $grades = Grade::with(['student.user', 'course.subject', 'course.classRoom'])->get();

        return Pdf::loadView('admin.reports.pdf.grades', compact('grades'))->stream('laporan-nilai.pdf');
    }

    public function gradesExcel(): BinaryFileResponse
    {
        return Excel::download(new GradesExport, 'laporan-nilai.xlsx');
    }

    public function attendancesPdf(): Response
    {
        $attendances = Attendance::with(['student.user', 'schedule.classRoom', 'schedule.course.subject'])->latest('date')->limit(500)->get();

        return Pdf::loadView('admin.reports.pdf.attendances', compact('attendances'))->stream('laporan-presensi.pdf');
    }

    public function attendancesExcel(): BinaryFileResponse
    {
        return Excel::download(new AttendancesExport, 'laporan-presensi.xlsx');
    }

    public function materialsPdf(): Response
    {
        $materials = Material::with(['course.subject', 'course.classRoom', 'teacher.user'])->get();

        return Pdf::loadView('admin.reports.pdf.materials', compact('materials'))->stream('laporan-materi.pdf');
    }

    public function materialsExcel(): BinaryFileResponse
    {
        return Excel::download(new MaterialsExport, 'laporan-materi.xlsx');
    }

    public function assignmentsPdf(): Response
    {
        $assignments = Assignment::with(['course.subject', 'course.classRoom'])->withCount('submissions')->get();

        return Pdf::loadView('admin.reports.pdf.assignments', compact('assignments'))->stream('laporan-tugas.pdf');
    }

    public function assignmentsExcel(): BinaryFileResponse
    {
        return Excel::download(new AssignmentsExport, 'laporan-tugas.xlsx');
    }

    public function examsPdf(): Response
    {
        $exams = Exam::with(['course.subject', 'course.classRoom'])->withCount('attempts')->get();

        return Pdf::loadView('admin.reports.pdf.exams', compact('exams'))->stream('laporan-ujian.pdf');
    }

    public function examsExcel(): BinaryFileResponse
    {
        return Excel::download(new ExamsExport, 'laporan-ujian.xlsx');
    }
}
