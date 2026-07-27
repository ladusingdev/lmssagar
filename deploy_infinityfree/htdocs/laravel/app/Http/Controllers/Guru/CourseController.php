<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::query()
            ->where('teacher_id', $request->user()->teacher->id)
            ->with(['subject', 'classRoom', 'academicYear'])
            ->withCount('enrollments')
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('guru.courses.index', compact('courses'));
    }

    public function show(Request $request, Course $course): View
    {
        abort_unless($course->teacher_id === $request->user()->teacher->id, 403);

        $course->load(['subject', 'classRoom', 'students.user']);

        return view('guru.courses.show', compact('course'));
    }
}
