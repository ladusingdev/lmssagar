<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::query()
            ->with(['subject', 'teacher.user', 'classRoom', 'academicYear'])
            ->withCount('enrollments')
            ->when($request->search, fn ($q) => $q->whereHas('subject', fn ($q2) => $q2->where('name', 'like', "%{$request->search}%")))
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'classes'));
    }

    public function create(): View
    {
        return view('admin.courses.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ]);

        if ($conflict = $this->findConflict($data)) {
            return back()->withInput()->with('error', "Mata pelajaran ini di kelas tersebut pada tahun ajaran yang dipilih sudah diampu oleh {$conflict->teacher->user->name}.");
        }

        $course = DB::transaction(function () use ($data) {
            $course = Course::create($data);

            $studentIds = ClassRoom::find($data['class_id'])->students()->pluck('id');
            $course->enrollments()->createMany(
                $studentIds->map(fn ($studentId) => ['student_id' => $studentId, 'enrolled_at' => now()])->all()
            );

            return $course;
        });

        ActivityLogger::log('create', "Menambahkan penugasan mengajar: {$course->subject->name} - {$course->classRoom->name}", $course);

        return redirect()->route('admin.courses.index')->with('success', 'Penugasan mengajar berhasil ditambahkan, siswa otomatis terdaftar.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', array_merge(['course' => $course], $this->formData()));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ]);

        if ($conflict = $this->findConflict($data, $course->id)) {
            return back()->withInput()->with('error', "Mata pelajaran ini di kelas tersebut pada tahun ajaran yang dipilih sudah diampu oleh {$conflict->teacher->user->name}.");
        }

        $course->update($data);
        ActivityLogger::log('update', "Memperbarui penugasan mengajar: {$course->subject->name}", $course);

        return redirect()->route('admin.courses.index')->with('success', 'Penugasan mengajar berhasil diperbarui.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        ActivityLogger::log('delete', "Menghapus penugasan mengajar: {$course->subject->name}");
        $course->delete();

        return back()->with('success', 'Penugasan mengajar berhasil dihapus.');
    }

    private function findConflict(array $data, ?int $ignoreCourseId = null): ?Course
    {
        return Course::with('teacher.user')
            ->where('subject_id', $data['subject_id'])
            ->where('class_id', $data['class_id'])
            ->where('academic_year_id', $data['academic_year_id'])
            ->when($ignoreCourseId, fn ($q) => $q->where('id', '!=', $ignoreCourseId))
            ->first();
    }

    private function formData(): array
    {
        return [
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::with('user')->get(),
            'classes' => ClassRoom::orderBy('name')->get(),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
        ];
    }
}
