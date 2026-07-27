<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizQuestionController extends Controller
{
    public function index(Quiz $quiz): View
    {
        $questions = $quiz->questions()->get();

        return view('admin.quizzes.questions.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz): View
    {
        return view('admin.quizzes.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        $data = $this->validated($request);
        $data['quiz_id'] = $quiz->id;
        $data['order'] = $quiz->questions()->max('order') + 1;

        QuizQuestion::create($data);
        ActivityLogger::log('create', "Menambahkan soal kuis: {$quiz->title}");

        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Quiz $quiz, QuizQuestion $question): View
    {
        return view('admin.quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        $question->update($this->validated($request));
        ActivityLogger::log('update', "Memperbarui soal kuis: {$quiz->title}");

        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        $question->delete();
        ActivityLogger::log('delete', "Menghapus soal kuis: {$quiz->title}");

        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Soal berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', 'in:multiple_choice,essay'],
            'question' => ['required', 'string'],
            'option_a' => ['nullable', 'string'],
            'option_b' => ['nullable', 'string'],
            'option_c' => ['nullable', 'string'],
            'option_d' => ['nullable', 'string'],
            'option_e' => ['nullable', 'string'],
            'correct_option' => ['nullable', 'in:A,B,C,D,E'],
            'score' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        if ($data['type'] === 'essay') {
            $data['correct_option'] = null;
        }

        return $data;
    }
}
