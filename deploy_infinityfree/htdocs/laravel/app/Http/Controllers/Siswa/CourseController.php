<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::query()
            ->whereHas('enrollments', fn ($q) => $q->where('student_id', $request->user()->student->id)->where('status', 'active'))
            ->with(['subject', 'teacher.user', 'classRoom'])
            ->get();

        return view('siswa.courses.index', compact('courses'));
    }
}
