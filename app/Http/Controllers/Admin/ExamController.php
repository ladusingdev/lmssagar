<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $exams = Exam::query()
            ->with(['course.subject', 'course.classRoom'])
            ->withCount('questions')
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.exams.index', compact('exams'));
    }

    public function create(): View
    {
        return view('admin.exams.create', ['courses' => Course::with(['subject', 'classRoom'])->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $course = Course::findOrFail($data['course_id']);
        $data['teacher_id'] = $course->teacher_id;

        $exam = Exam::create($data);
        ActivityLogger::log('create', "Menambahkan ujian: {$exam->title}", $exam);

        return redirect()->route('admin.exams.questions.index', $exam)->with('success', 'Ujian berhasil ditambahkan. Silakan tambahkan soal.');
    }

    public function edit(Exam $exam): View
    {
        return view('admin.exams.edit', ['exam' => $exam, 'courses' => Course::with(['subject', 'classRoom'])->get()]);
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $data = $this->validated($request);
        $course = Course::findOrFail($data['course_id']);
        $data['teacher_id'] = $course->teacher_id;

        $exam->update($data);
        ActivityLogger::log('update', "Memperbarui ujian: {$exam->title}", $exam);

        return redirect()->route('admin.exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        ActivityLogger::log('delete', "Menghapus ujian: {$exam->title}");
        $exam->delete();

        return back()->with('success', 'Ujian berhasil dihapus.');
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
}
