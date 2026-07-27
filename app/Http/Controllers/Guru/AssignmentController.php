<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Notifications\GeneralNotification;
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
            ->where('teacher_id', $request->user()->teacher->id)
            ->with(['course.subject', 'course.classRoom'])
            ->withCount('submissions')
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('guru.assignments.index', compact('assignments'));
    }

    public function create(Request $request): View
    {
        return view('guru.assignments.create', ['courses' => $this->myCourses($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->authorizeCourse($request, $data['course_id']);

        $assignment = new Assignment([
            'course_id' => $data['course_id'],
            'teacher_id' => $request->user()->teacher->id,
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

        $course = Course::with('students.user')->find($data['course_id']);
        foreach ($course->students as $student) {
            $student->user->notify(new GeneralNotification(
                'Tugas Baru',
                "Tugas baru \"{$assignment->title}\" telah ditambahkan untuk {$course->subject->name}.",
                route('siswa.assignments.show', $assignment)
            ));
        }

        ActivityLogger::log('create', "Menambahkan tugas: {$assignment->title}", $assignment);

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Request $request, Assignment $assignment): View
    {
        abort_unless($assignment->teacher_id === $request->user()->teacher->id, 403);

        return view('guru.assignments.edit', ['assignment' => $assignment, 'courses' => $this->myCourses($request)]);
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        abort_unless($assignment->teacher_id === $request->user()->teacher->id, 403);

        $data = $this->validated($request);
        $this->authorizeCourse($request, $data['course_id']);

        $assignment->fill([
            'course_id' => $data['course_id'],
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

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Request $request, Assignment $assignment): RedirectResponse
    {
        abort_unless($assignment->teacher_id === $request->user()->teacher->id, 403);

        if ($assignment->attachment_path) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }
        DB::table('notifications')->where('data->url', route('siswa.assignments.show', $assignment))->delete();
        ActivityLogger::log('delete', "Menghapus tugas: {$assignment->title}");
        $assignment->delete();

        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:20480'],
            'deadline' => ['required', 'date'],
            'max_score' => ['required', 'integer', 'min:1', 'max:100'],
            'allow_late' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);
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
