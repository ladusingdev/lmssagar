<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamQuestionController extends Controller
{
    public function index(Request $request, Exam $exam): View
    {
        $this->authorizeExam($request, $exam);
        $questions = $exam->questions()->get();

        return view('guru.exams.questions.index', compact('exam', 'questions'));
    }

    public function create(Request $request, Exam $exam): View
    {
        $this->authorizeExam($request, $exam);

        return view('guru.exams.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam): RedirectResponse
    {
        $this->authorizeExam($request, $exam);

        $data = $this->validated($request);
        $data['exam_id'] = $exam->id;
        $data['order'] = $exam->questions()->max('order') + 1;

        ExamQuestion::create($data);
        ActivityLogger::log('create', "Menambahkan soal ujian: {$exam->title}");

        return redirect()->route('guru.exams.questions.index', $exam)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Request $request, Exam $exam, ExamQuestion $question): View
    {
        $this->authorizeExam($request, $exam);

        return view('guru.exams.questions.edit', compact('exam', 'question'));
    }

    public function update(Request $request, Exam $exam, ExamQuestion $question): RedirectResponse
    {
        $this->authorizeExam($request, $exam);

        $question->update($this->validated($request));
        ActivityLogger::log('update', "Memperbarui soal ujian: {$exam->title}");

        return redirect()->route('guru.exams.questions.index', $exam)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Request $request, Exam $exam, ExamQuestion $question): RedirectResponse
    {
        $this->authorizeExam($request, $exam);

        $question->delete();
        ActivityLogger::log('delete', "Menghapus soal ujian: {$exam->title}");

        return redirect()->route('guru.exams.questions.index', $exam)->with('success', 'Soal berhasil dihapus.');
    }

    private function authorizeExam(Request $request, Exam $exam): void
    {
        abort_unless($exam->teacher_id === $request->user()->teacher->id, 403);
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
