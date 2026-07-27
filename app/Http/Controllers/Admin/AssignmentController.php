<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $assignments = Assignment::query()
            ->with(['course.subject', 'course.classRoom', 'teacher.user'])
            ->withCount('submissions')
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        return view('admin.assignments.create', ['courses' => Course::with(['subject', 'classRoom', 'teacher.user'])->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:20480'],
            'deadline' => ['required', 'date'],
            'max_score' => ['required', 'integer', 'min:1', 'max:100'],
            'allow_late' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $course = Course::findOrFail($data['course_id']);

        $assignment = new Assignment([
            'course_id' => $data['course_id'],
            'teacher_id' => $course->teacher_id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'deadline' => $data['deadline'],
            'max_score' => $data['max_score'],
            'allow_late' => $request->boolean('allow_late'),
            'is_published' => $request->boolean('is_published', true),
        ]);

        if ($request->hasFile('attachment')) {
            $assignment->attachment_path = $request->file('attachment')->store('assignments', 'public');
        }

        $assignment->save();
        ActivityLogger::log('create', "Menambahkan tugas: {$assignment->title}", $assignment);

        return redirect()->route('admin.assignments.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Assignment $assignment): View
    {
        return view('admin.assignments.edit', ['assignment' => $assignment, 'courses' => Course::with(['subject', 'classRoom', 'teacher.user'])->get()]);
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:20480'],
            'deadline' => ['required', 'date'],
            'max_score' => ['required', 'integer', 'min:1', 'max:100'],
            'allow_late' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $course = Course::findOrFail($data['course_id']);

        $assignment->fill([
            'course_id' => $data['course_id'],
            'teacher_id' => $course->teacher_id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'deadline' => $data['deadline'],
            'max_score' => $data['max_score'],
            'allow_late' => $request->boolean('allow_late'),
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('attachment')) {
            if ($assignment->attachment_path) {
                Storage::disk('public')->delete($assignment->attachment_path);
            }
            $assignment->attachment_path = $request->file('attachment')->store('assignments', 'public');
        }

        $assignment->save();
        ActivityLogger::log('update', "Memperbarui tugas: {$assignment->title}", $assignment);

        return redirect()->route('admin.assignments.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        if ($assignment->attachment_path) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }
        DB::table('notifications')->where('data->url', route('siswa.assignments.show', $assignment))->delete();
        ActivityLogger::log('delete', "Menghapus tugas: {$assignment->title}");
        $assignment->delete();

        return back()->with('success', 'Tugas berhasil dihapus.');
    }
}
