<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Services\ActivityLogger;
use App\Services\GradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $exams = Exam::query()
            ->where('is_published', true)
            ->whereHas('course.enrollments', fn ($q) => $q->where('student_id', $studentId))
            ->with(['course.subject'])
            ->withCount('questions')
            ->latest('start_time')
            ->paginate(12);

        $attemptStatus = ExamAttempt::where('student_id', $studentId)->pluck('status', 'exam_id');

        return view('siswa.exams.index', compact('exams', 'attemptStatus'));
    }

    public function show(Request $request, Exam $exam): View
    {
        $this->authorizeAccess($request, $exam);

        $attempt = ExamAttempt::where('exam_id', $exam->id)->where('student_id', $request->user()->student->id)->first();

        return view('siswa.exams.show', compact('exam', 'attempt'));
    }

    public function start(Request $request, Exam $exam): RedirectResponse
    {
        $this->authorizeAccess($request, $exam);
        abort_unless($exam->isOpen(), 403, 'Ujian belum dibuka atau sudah ditutup.');

        $student = $request->user()->student;
        $attempt = ExamAttempt::firstOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $student->id],
            ['started_at' => now(), 'status' => 'in_progress']
        );

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('siswa.exams.result', $exam);
        }

        return redirect()->route('siswa.exams.attempt', $exam);
    }

    public function attempt(Request $request, Exam $exam): View|RedirectResponse
    {
        $this->authorizeAccess($request, $exam);
        $student = $request->user()->student;

        $attempt = ExamAttempt::where('exam_id', $exam->id)->where('student_id', $student->id)->first();
        abort_unless($attempt && $attempt->status === 'in_progress', 404);

        $questionsQuery = $exam->shuffle_questions
            ? $exam->questions()->inRandomOrder($attempt->id)
            : $exam->questions()->orderBy('order');

        $questions = $exam->questions_to_show
            ? $questionsQuery->limit($exam->questions_to_show)->get()
            : $questionsQuery->get();

        $answers = ExamAnswer::where('exam_attempt_id', $attempt->id)->get()->keyBy('exam_question_id');

        $endsAt = min($attempt->started_at->copy()->addMinutes($exam->duration_minutes), $exam->end_time);

        if (now()->greaterThanOrEqualTo($endsAt)) {
            $this->finalize($exam, $attempt, $questions->pluck('id'));

            return redirect()->route('siswa.exams.result', $exam);
        }

        return view('siswa.exams.attempt', compact('exam', 'attempt', 'questions', 'answers', 'endsAt'));
    }

    public function answer(Request $request, Exam $exam): JsonResponse
    {
        $student = $request->user()->student;
        $attempt = ExamAttempt::where('exam_id', $exam->id)->where('student_id', $student->id)->where('status', 'in_progress')->firstOrFail();

        $data = $request->validate([
            'question_id' => ['required', 'exists:exam_questions,id'],
            'selected_option' => ['nullable', 'in:A,B,C,D,E'],
            'answer_text' => ['nullable', 'string'],
        ]);

        $question = $exam->questions()->findOrFail($data['question_id']);
        $isCorrect = $question->type === 'multiple_choice' ? ($data['selected_option'] ?? null) === $question->correct_option : null;

        ExamAnswer::updateOrCreate(
            ['exam_attempt_id' => $attempt->id, 'exam_question_id' => $question->id],
            [
                'selected_option' => $data['selected_option'] ?? null,
                'answer_text' => $data['answer_text'] ?? null,
                'is_correct' => $isCorrect,
                'score' => $question->type === 'multiple_choice' ? ($isCorrect ? $question->score : 0) : null,
            ]
        );

        return response()->json(['saved' => true]);
    }

    public function submit(Request $request, Exam $exam): RedirectResponse
    {
        $student = $request->user()->student;
        $attempt = ExamAttempt::where('exam_id', $exam->id)->where('student_id', $student->id)->where('status', 'in_progress')->firstOrFail();

        $questionIds = $request->input('question_ids', []);
        $this->finalize($exam, $attempt, collect($questionIds));
        ActivityLogger::log('submit', "Mengumpulkan ujian: {$exam->title}", $attempt);

        return redirect()->route('siswa.exams.result', $exam)->with('success', 'Ujian berhasil dikumpulkan.');
    }

    public function result(Request $request, Exam $exam): View
    {
        $this->authorizeAccess($request, $exam);

        $attempt = ExamAttempt::with('answers.question')
            ->where('exam_id', $exam->id)
            ->where('student_id', $request->user()->student->id)
            ->firstOrFail();

        return view('siswa.exams.result', compact('exam', 'attempt'));
    }

    private function finalize(Exam $exam, ExamAttempt $attempt, $questionIds): void
    {
        $hasEssay = $exam->questions()->whereIn('id', $questionIds)->where('type', 'essay')->exists();
        $totalScore = ExamAnswer::where('exam_attempt_id', $attempt->id)->sum('score');

        $attempt->update([
            'submitted_at' => now(),
            'score' => $totalScore,
            'is_passed' => $hasEssay ? null : $totalScore >= $exam->passing_score,
            'status' => $hasEssay ? 'submitted' : 'graded',
        ]);

        if (! $hasEssay) {
            GradeService::recalculateForCourse($exam->course);
        }
    }

    private function authorizeAccess(Request $request, Exam $exam): void
    {
        $studentId = $request->user()->student->id;
        abort_unless(
            $exam->is_published && $exam->course->enrollments()->where('student_id', $studentId)->exists(),
            403
        );
    }
}
