<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Discussion;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $discussions = Discussion::query()
            ->whereHas('course.enrollments', fn ($q) => $q->where('student_id', $studentId))
            ->with(['course.subject', 'user'])
            ->withCount('allComments')
            ->latest()
            ->paginate(15);

        return view('siswa.discussions.index', compact('discussions'));
    }

    public function create(Request $request): View
    {
        return view('siswa.discussions.create', ['courses' => $this->myCourses($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $this->authorizeCourse($request, $data['course_id']);

        $discussion = Discussion::create([
            'course_id' => $data['course_id'],
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        ActivityLogger::log('create', "Membuat thread diskusi: {$discussion->title}", $discussion);

        return redirect()->route('siswa.discussions.show', $discussion)->with('success', 'Thread diskusi berhasil dibuat.');
    }

    public function show(Request $request, Discussion $discussion): View
    {
        $this->authorizeCourse($request, $discussion->course_id);
        $discussion->load(['comments.user', 'comments.replies.user']);

        return view('siswa.discussions.show', compact('discussion'));
    }

    public function edit(Request $request, Discussion $discussion): View
    {
        abort_unless($discussion->user_id === $request->user()->id, 403);

        return view('siswa.discussions.edit', compact('discussion'));
    }

    public function update(Request $request, Discussion $discussion): RedirectResponse
    {
        abort_unless($discussion->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $discussion->update($data);

        return redirect()->route('siswa.discussions.index')->with('success', 'Thread berhasil diperbarui.');
    }

    public function destroy(Request $request, Discussion $discussion): RedirectResponse
    {
        abort_unless($discussion->user_id === $request->user()->id, 403);

        $discussion->delete();

        return redirect()->route('siswa.discussions.index')->with('success', 'Thread berhasil dihapus.');
    }

    public function storeComment(Request $request, Discussion $discussion): RedirectResponse
    {
        $this->authorizeCourse($request, $discussion->course_id);
        abort_if($discussion->is_locked, 403, 'Thread ini telah dikunci.');

        $data = $request->validate([
            'body' => ['required', 'string'],
            'parent_id' => ['nullable', 'exists:discussion_comments,id'],
        ]);

        $discussion->allComments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $data['parent_id'] ?? null,
            'body' => $data['body'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    private function myCourses(Request $request)
    {
        $studentId = $request->user()->student->id;

        return Course::whereHas('enrollments', fn ($q) => $q->where('student_id', $studentId))->with(['subject', 'classRoom'])->get();
    }

    private function authorizeCourse(Request $request, int $courseId): void
    {
        $studentId = $request->user()->student->id;
        abort_unless(
            Course::where('id', $courseId)->whereHas('enrollments', fn ($q) => $q->where('student_id', $studentId))->exists(),
            403
        );
    }
}
