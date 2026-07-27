<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $grades = Grade::where('student_id', $request->user()->student->id)
            ->with(['course.subject', 'academicYear'])
            ->latest()
            ->get();

        return view('siswa.grades.index', compact('grades'));
    }
}
