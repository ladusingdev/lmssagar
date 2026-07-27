<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Grade;
use App\Services\ActivityLogger;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $grades = Grade::query()
            ->with(['student.user', 'course.subject', 'course.classRoom', 'academicYear'])
            ->when($request->class_id, fn ($q) => $q->whereHas('course', fn ($q2) => $q2->where('class_id', $request->class_id)))
            ->when($request->course_id, fn ($q) => $q->where('course_id', $request->course_id))
            ->when($request->search, fn ($q) => $q->whereHas('student.user', fn ($q2) => $q2->where('name', 'like', "%{$request->search}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $classes = ClassRoom::orderBy('name')->get();
        $courses = Course::with('subject')->get();

        return view('admin.grades.index', compact('grades', 'classes', 'courses'));
    }

    public function edit(Grade $grade): View
    {
        return view('admin.grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $data = $request->validate([
            'final_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['letter_grade'] = Grade::letterFor($data['final_score']);
        $grade->update($data);

        ActivityLogger::log('update', "Memperbarui nilai akhir: {$grade->student->user->name}", $grade);

        return back()->with('success', 'Nilai berhasil diperbarui.');
    }

    public function recalculate(Request $request, Course $course): RedirectResponse
    {
        GradeService::recalculateForCourse($course);
        ActivityLogger::log('update', "Menghitung ulang nilai: {$course->subject->name} - {$course->classRoom->name}", $course);

        return back()->with('success', 'Nilai berhasil dihitung ulang.');
    }
}
