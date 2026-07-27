<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Notifications\GeneralNotification;
use App\Services\ActivityLogger;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(Request $request, Assignment $assignment): View
    {
        abort_unless($assignment->teacher_id === $request->user()->teacher->id, 403);

        $submissions = $assignment->submissions()->with('student.user')->latest('submitted_at')->get();
        $enrolledCount = $assignment->course->enrollments()->count();

        return view('guru.submissions.index', compact('assignment', 'submissions', 'enrolledCount'));
    }

    public function edit(Request $request, Submission $submission): View
    {
        abort_unless($submission->assignment->teacher_id === $request->user()->teacher->id, 403);

        $submission->load('student.user', 'assignment');

        return view('guru.submissions.edit', compact('submission'));
    }

    public function update(Request $request, Submission $submission): RedirectResponse
    {
        abort_unless($submission->assignment->teacher_id === $request->user()->teacher->id, 403);

        $data = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:'.$submission->assignment->max_score],
            'feedback' => ['nullable', 'string'],
        ]);

        $submission->update([
            'score' => $data['score'],
            'feedback' => $data['feedback'] ?? null,
            'graded_by' => $request->user()->teacher->id,
            'graded_at' => now(),
            'status' => 'graded',
        ]);

        GradeService::recalculateForCourse($submission->assignment->course);

        $submission->student->user->notify(new GeneralNotification(
            'Tugas Dinilai',
            "Tugas \"{$submission->assignment->title}\" telah dinilai: {$data['score']}.",
            route('siswa.assignments.show', $submission->assignment)
        ));

        ActivityLogger::log('grade', "Menilai tugas: {$submission->student->user->name}", $submission);

        return redirect()->route('guru.assignments.submissions.index', $submission->assignment)->with('success', 'Nilai berhasil disimpan.');
    }
}
