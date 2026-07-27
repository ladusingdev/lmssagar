<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\ActivityLogger;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $exams = Exam::query()
            ->where('teacher_id', $request->user()->teacher->id)
            ->with(['course.subject', 'course.classRoom'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->paginate(15);

        return view('guru.exams.index', compact('exams'));
    }

    public function create(Request $request): View
    {
        return view('guru.exams.create', ['courses' => $this->myCourses($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->authorizeCourse($request, $data['course_id']);
        $data['teacher_id'] = $request->user()->teacher->id;

        $exam = Exam::create($data);
        ActivityLogger::log('create', "Menambahkan ujian: {$exam->title}", $exam);

        return redirect()->route('guru.exams.questions.index', $exam)->with('success', 'Ujian berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit(Request $request, Exam $exam): View
    {
        abort_unless($exam->teacher_id === $request->user()->teacher->id, 403);

        return view('guru.exams.edit', ['exam' => $exam, 'courses' => $this->myCourses($request)]);
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        abort_unless($exam->teacher_id === $request->user()->teacher->id, 403);

        $data = $this->validated($request);
        $this->authorizeCourse($request, $data['course_id']);

        $exam->update($data);
        ActivityLogger::log('update', "Memperbarui ujian: {$exam->title}", $exam);

        return redirect()->route('guru.exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Request $request, Exam $exam): RedirectResponse
    {
        abort_unless($exam->teacher_id === $request->user()->teacher->id, 403);

        ActivityLogger::log('delete', "Menghapus ujian: {$exam->title}");
        $exam->delete();

        return back()->with('success', 'Ujian berhasil dihapus.');
    }

    public function results(Request $request, Exam $exam): View
    {
        abort_unless($exam->teacher_id === $request->user()->teacher->id, 403);

        $attempts = $exam->attempts()->with('student.user')->orderByDesc('score')->get();

        return view('guru.exams.results', compact('exam', 'attempts'));
    }

    public function reviewAttempt(Request $request, Exam $exam, ExamAttempt $attempt): View
    {
        abort_unless($exam->teacher_id === $request->user()->teacher->id, 403);

        $attempt->load(['answers.question', 'student.user']);

        return view('guru.exams.review', compact('exam', 'attempt'));
    }

    public function updateAttemptScore(Request $request, Exam $exam, ExamAttempt $attempt): RedirectResponse
    {
        abort_unless($exam->teacher_id === $request->user()->teacher->id, 403);

        $scores = $request->input('scores', []);

        foreach ($attempt->answers as $answer) {
            if (isset($scores[$answer->id]) && $answer->question->type === 'essay') {
                $answer->update(['score' => min((float) $scores[$answer->id], $answer->question->score)]);
            }
        }

        $totalScore = $attempt->answers()->sum('score');
        $attempt->update([
            'score' => $totalScore,
            'is_passed' => $totalScore >= $exam->passing_score,
            'status' => 'graded',
        ]);

        GradeService::recalculateForCourse($exam->course);
        ActivityLogger::log('grade', "Menilai essay ujian: {$attempt->student->user->name}", $attempt);

        return redirect()->route('guru.exams.results', $exam)->with('success', 'Nilai essay berhasil disimpan.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'questions_to_show' => ['nullable', 'integer', 'min:1'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $data['shuffle_questions'] = $request->boolean('shuffle_questions');
        $data['is_published'] = $request->boolean('is_published', true);

        return $data;
    }

    private function myCourses(Request $request)
    {
        return Course::where('teacher_id', $request->user()->teacher->id)->with(['subject', 'classRoom'])->get();
    }

    private function authorizeCourse(Request $request, int $courseId): void
    {
        abort_unless(
            Course::where('id', $courseId)->where('teacher_id', $request->user()->teacher->id)->exists(),
            403
        );
    }
}
