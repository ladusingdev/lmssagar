<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $quizzes = Quiz::query()
            ->with(['course.subject', 'course.classRoom'])
            ->withCount('questions')
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        return view('admin.quizzes.create', ['courses' => Course::with(['subject', 'classRoom'])->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $course = Course::findOrFail($data['course_id']);
        $data['teacher_id'] = $course->teacher_id;

        $quiz = Quiz::create($data);
        ActivityLogger::log('create', "Menambahkan kuis: {$quiz->title}", $quiz);

        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Kuis berhasil ditambahkan. Silakan tambahkan soal.');
    }

    public function edit(Quiz $quiz): View
    {
        return view('admin.quizzes.edit', ['quiz' => $quiz, 'courses' => Course::with(['subject', 'classRoom'])->get()]);
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $data = $this->validated($request);
        $course = Course::findOrFail($data['course_id']);
        $data['teacher_id'] = $course->teacher_id;

        $quiz->update($data);
        ActivityLogger::log('update', "Memperbarui kuis: {$quiz->title}", $quiz);

        return redirect()->route('admin.quizzes.index')->with('success', 'Kuis berhasil diperbarui.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        ActivityLogger::log('delete', "Menghapus kuis: {$quiz->title}");
        $quiz->delete();

        return back()->with('success', 'Kuis berhasil dihapus.');
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
}
