<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Notifications\GeneralNotification;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $quizzes = Quiz::query()
            ->where('teacher_id', $request->user()->teacher->id)
            ->with(['course.subject', 'course.classRoom'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->paginate(15);

        return view('guru.quizzes.index', compact('quizzes'));
    }

    public function create(Request $request): View
    {
        return view('guru.quizzes.create', ['courses' => $this->myCourses($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->authorizeCourse($request, $data['course_id']);
        $data['teacher_id'] = $request->user()->teacher->id;

        $quiz = Quiz::create($data);

        $course = Course::with('students.user', 'subject')->find($data['course_id']);
        foreach ($course->students as $student) {
            $student->user->notify(new GeneralNotification(
                'Kuis Baru',
                "Kuis baru \"{$quiz->title}\" telah ditambahkan untuk {$course->subject->name}.",
                route('siswa.quizzes.show', $quiz)
            ));
        }

        ActivityLogger::log('create', "Menambahkan kuis: {$quiz->title}", $quiz);

        return redirect()->route('guru.quizzes.questions.index', $quiz)->with('success', 'Kuis berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit(Request $request, Quiz $quiz): View
    {
        abort_unless($quiz->teacher_id === $request->user()->teacher->id, 403);

        return view('guru.quizzes.edit', ['quiz' => $quiz, 'courses' => $this->myCourses($request)]);
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        abort_unless($quiz->teacher_id === $request->user()->teacher->id, 403);

        $data = $this->validated($request);
        $this->authorizeCourse($request, $data['course_id']);

        $quiz->update($data);
        ActivityLogger::log('update', "Memperbarui kuis: {$quiz->title}", $quiz);

        return redirect()->route('guru.quizzes.index')->with('success', 'Kuis berhasil diperbarui.');
    }

    public function destroy(Request $request, Quiz $quiz): RedirectResponse
    {
        abort_unless($quiz->teacher_id === $request->user()->teacher->id, 403);

        ActivityLogger::log('delete', "Menghapus kuis: {$quiz->title}");
        $quiz->delete();

        return back()->with('success', 'Kuis berhasil dihapus.');
    }

    public function results(Request $request, Quiz $quiz): View
    {
        abort_unless($quiz->teacher_id === $request->user()->teacher->id, 403);

        $attempts = $quiz->attempts()->with('student.user')->orderByDesc('score')->get();

        return view('guru.quizzes.results', compact('quiz', 'attempts'));
    }

    public function reviewAttempt(Request $request, Quiz $quiz, \App\Models\QuizAttempt $attempt): View
    {
        abort_unless($quiz->teacher_id === $request->user()->teacher->id, 403);

        $attempt->load(['answers.question', 'student.user']);

        return view('guru.quizzes.review', compact('quiz', 'attempt'));
    }

    public function updateAttemptScore(Request $request, Quiz $quiz, \App\Models\QuizAttempt $attempt): RedirectResponse
    {
        abort_unless($quiz->teacher_id === $request->user()->teacher->id, 403);

        $scores = $request->input('scores', []);

        foreach ($attempt->answers as $answer) {
            if (isset($scores[$answer->id]) && $answer->question->type === 'essay') {
                $answer->update(['score' => min((float) $scores[$answer->id], $answer->question->score)]);
            }
        }

        $attempt->update([
            'score' => $attempt->answers()->sum('score'),
            'status' => 'graded',
        ]);

        \App\Services\GradeService::recalculateForCourse($quiz->course);
        ActivityLogger::log('grade', "Menilai essay kuis: {$attempt->student->user->name}", $attempt);

        return redirect()->route('guru.quizzes.results', $quiz)->with('success', 'Nilai essay berhasil disimpan.');
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
        ]);

        $data['shuffle_questions'] = $request->boolean('shuffle_questions');
        $data['show_result_immediately'] = $request->boolean('show_result_immediately');
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
