<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $assignments = Assignment::query()
            ->where('is_published', true)
            ->whereHas('course.enrollments', fn ($q) => $q->where('student_id', $studentId))
            ->with(['course.subject'])
            ->withCount(['submissions' => fn ($q) => $q->where('student_id', $studentId)])
            ->latest('deadline')
            ->paginate(12);

        $mySubmissions = $request->user()->student->submissions()->pluck('score', 'assignment_id');

        return view('siswa.assignments.index', compact('assignments', 'mySubmissions'));
    }

    public function show(Request $request, Assignment $assignment): View
    {
        $this->authorizeAccess($request, $assignment);

        $submission = $assignment->submissions()->where('student_id', $request->user()->student->id)->first();

        return view('siswa.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->authorizeAccess($request, $assignment);

        if (! $assignment->allow_late && $assignment->isPastDeadline()) {
            return back()->with('error', 'Batas waktu pengumpulan telah berakhir.');
        }

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar,jpg,jpeg,png', 'max:20480'],
            'note' => ['nullable', 'string'],
        ]);

        $student = $request->user()->student;
        $file = $data['file'];

        $submission = \App\Models\Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            [
                'file_path' => $file->store('submissions', 'public'),
                'file_name' => $file->getClientOriginalName(),
                'note' => $data['note'] ?? null,
                'submitted_at' => now(),
                'status' => $assignment->isPastDeadline() ? 'late' : 'submitted',
                'score' => null,
                'graded_by' => null,
                'graded_at' => null,
            ]
        );

        ActivityLogger::log('create', "Mengumpulkan tugas: {$assignment->title}", $submission);

        return redirect()->route('siswa.assignments.show', $assignment)->with('success', 'Tugas berhasil dikumpulkan.');
    }

    private function authorizeAccess(Request $request, Assignment $assignment): void
    {
        $studentId = $request->user()->student->id;
        abort_unless(
            $assignment->is_published && $assignment->course->enrollments()->where('student_id', $studentId)->exists(),
            403
        );
    }
}
