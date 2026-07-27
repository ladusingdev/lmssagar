<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
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
        $courses = Course::where('teacher_id', $request->user()->teacher->id)->with(['subject', 'classRoom'])->get();

        $grades = Grade::query()
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->teacher->id))
            ->when($request->course_id, fn ($q) => $q->where('course_id', $request->course_id))
            ->with(['student.user', 'course.subject', 'course.classRoom'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('guru.grades.index', compact('grades', 'courses'));
    }

    public function recalculate(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->teacher_id === $request->user()->teacher->id, 403);

        GradeService::recalculateForCourse($course);
        ActivityLogger::log('update', "Menghitung ulang nilai: {$course->subject->name} - {$course->classRoom->name}", $course);

        return back()->with('success', 'Nilai berhasil dihitung ulang.');
    }
}
