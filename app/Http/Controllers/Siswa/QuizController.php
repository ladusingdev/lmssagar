<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Services\ActivityLogger;
use App\Services\GradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $quizzes = Quiz::query()
            ->where('is_published', true)
            ->whereHas('course.enrollments', fn ($q) => $q->where('student_id', $studentId))
            ->with(['course.subject'])
            ->withCount('questions')
            ->latest('start_time')
            ->paginate(12);

        $attempts = QuizAttempt::where('student_id', $studentId)->pluck('score', 'quiz_id');
        $attemptStatus = QuizAttempt::where('student_id', $studentId)->pluck('status', 'quiz_id');

        return view('siswa.quizzes.index', compact('quizzes', 'attempts', 'attemptStatus'));
    }

    public function show(Request $request, Quiz $quiz): View
    {
        $this->authorizeAccess($request, $quiz);

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $request->user()->student->id)->first();

        return view('siswa.quizzes.show', compact('quiz', 'attempt'));
    }

    public function start(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorizeAccess($request, $quiz);
        abort_unless($quiz->isOpen(), 403, 'Kuis belum dibuka atau sudah ditutup.');

        $student = $request->user()->student;
        $attempt = QuizAttempt::firstOrCreate(
            ['quiz_id' => $quiz->id, 'student_id' => $student->id],
            ['started_at' => now(), 'status' => 'in_progress']
        );

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('siswa.quizzes.result', $quiz);
        }

        return redirect()->route('siswa.quizzes.attempt', $quiz);
    }

    public function attempt(Request $request, Quiz $quiz): View|RedirectResponse
    {
        $this->authorizeAccess($request, $quiz);
        $student = $request->user()->student;

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->first();
        abort_unless($attempt && $attempt->status === 'in_progress', 404);

        $questions = $quiz->shuffle_questions
            ? $quiz->questions()->inRandomOrder()->get()
            : $quiz->questions()->get();

        $answers = QuizAnswer::where('quiz_attempt_id', $attempt->id)->get()->keyBy('quiz_question_id');

        $endsAt = min($attempt->started_at->copy()->addMinutes($quiz->duration_minutes), $quiz->end_time);

        if (now()->greaterThanOrEqualTo($endsAt)) {
            $this->finalize($quiz, $attempt);

            return redirect()->route('siswa.quizzes.result', $quiz);
        }

        return view('siswa.quizzes.attempt', compact('quiz', 'attempt', 'questions', 'answers', 'endsAt'));
    }

    public function answer(Request $request, Quiz $quiz): JsonResponse
    {
        $student = $request->user()->student;
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->where('status', 'in_progress')->firstOrFail();

        $data = $request->validate([
            'question_id' => ['required', 'exists:quiz_questions,id'],
            'selected_option' => ['nullable', 'in:A,B,C,D,E'],
            'answer_text' => ['nullable', 'string'],
        ]);

        $question = $quiz->questions()->findOrFail($data['question_id']);
        $isCorrect = $question->type === 'multiple_choice' ? ($data['selected_option'] ?? null) === $question->correct_option : null;

        QuizAnswer::updateOrCreate(
            ['quiz_attempt_id' => $attempt->id, 'quiz_question_id' => $question->id],
            [
                'selected_option' => $data['selected_option'] ?? null,
                'answer_text' => $data['answer_text'] ?? null,
                'is_correct' => $isCorrect,
                'score' => $question->type === 'multiple_choice' ? ($isCorrect ? $question->score : 0) : null,
            ]
        );

        return response()->json(['saved' => true]);
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $student = $request->user()->student;
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->where('status', 'in_progress')->firstOrFail();

        $this->finalize($quiz, $attempt);
        ActivityLogger::log('submit', "Mengumpulkan kuis: {$quiz->title}", $attempt);

        return redirect()->route('siswa.quizzes.result', $quiz)->with('success', 'Kuis berhasil dikumpulkan.');
    }

    public function result(Request $request, Quiz $quiz): View
    {
        $this->authorizeAccess($request, $quiz);

        $attempt = QuizAttempt::with('answers.question')
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $request->user()->student->id)
            ->firstOrFail();

        return view('siswa.quizzes.result', compact('quiz', 'attempt'));
    }

    private function finalize(Quiz $quiz, QuizAttempt $attempt): void
    {
        $hasEssay = $quiz->questions()->where('type', 'essay')->exists();
        $totalScore = QuizAnswer::where('quiz_attempt_id', $attempt->id)->sum('score');

        $attempt->update([
            'submitted_at' => now(),
            'score' => $totalScore,
            'status' => $hasEssay ? 'submitted' : 'graded',
        ]);

        if (! $hasEssay) {
            GradeService::recalculateForCourse($quiz->course);
        }
    }

    private function authorizeAccess(Request $request, Quiz $quiz): void
    {
        $studentId = $request->user()->student->id;
        abort_unless(
            $quiz->is_published && $quiz->course->enrollments()->where('student_id', $studentId)->exists(),
            403
        );
    }
}
