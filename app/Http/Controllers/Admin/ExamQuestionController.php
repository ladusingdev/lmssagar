<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamQuestionController extends Controller
{
    public function index(Exam $exam): View
    {
        $questions = $exam->questions()->get();

        return view('admin.exams.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam): View
    {
        return view('admin.exams.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam): RedirectResponse
    {
        $data = $this->validated($request);
        $data['exam_id'] = $exam->id;
        $data['order'] = $exam->questions()->max('order') + 1;

        ExamQuestion::create($data);
        ActivityLogger::log('create', "Menambahkan soal ujian: {$exam->title}");

        return redirect()->route('admin.exams.questions.index', $exam)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Exam $exam, ExamQuestion $question): View
    {
        return view('admin.exams.questions.edit', compact('exam', 'question'));
    }

    public function update(Request $request, Exam $exam, ExamQuestion $question): RedirectResponse
    {
        $question->update($this->validated($request));
        ActivityLogger::log('update', "Memperbarui soal ujian: {$exam->title}");

        return redirect()->route('admin.exams.questions.index', $exam)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Exam $exam, ExamQuestion $question): RedirectResponse
    {
        $question->delete();
        ActivityLogger::log('delete', "Menghapus soal ujian: {$exam->title}");

        return redirect()->route('admin.exams.questions.index', $exam)->with('success', 'Soal berhasil dihapus.');
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
