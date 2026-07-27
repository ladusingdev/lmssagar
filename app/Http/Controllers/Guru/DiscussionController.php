<?php

namespace App\Http\Controllers\Guru;

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
        $discussions = Discussion::query()
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->teacher->id))
            ->with(['course.subject', 'user'])
            ->withCount('allComments')
            ->latest()
            ->paginate(15);

        return view('guru.discussions.index', compact('discussions'));
    }

    public function create(Request $request): View
    {
        return view('guru.discussions.create', ['courses' => $this->myCourses($request)]);
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

        return redirect()->route('guru.discussions.show', $discussion)->with('success', 'Thread diskusi berhasil dibuat.');
    }

    public function show(Request $request, Discussion $discussion): View
    {
        $this->authorizeCourse($request, $discussion->course_id);
        $discussion->load(['comments.user', 'comments.replies.user']);

        return view('guru.discussions.show', compact('discussion'));
    }

    public function edit(Request $request, Discussion $discussion): View
    {
        abort_unless($discussion->user_id === $request->user()->id, 403);

        return view('guru.discussions.edit', ['discussion' => $discussion, 'courses' => $this->myCourses($request)]);
    }

    public function update(Request $request, Discussion $discussion): RedirectResponse
    {
        abort_unless($discussion->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_locked' => ['nullable', 'boolean'],
        ]);

        $discussion->update([
            'title' => $data['title'],
            'body' => $data['body'],
            'is_locked' => $request->boolean('is_locked'),
        ]);

        return redirect()->route('guru.discussions.index')->with('success', 'Thread berhasil diperbarui.');
    }

    public function destroy(Request $request, Discussion $discussion): RedirectResponse
    {
        $this->authorizeCourse($request, $discussion->course_id);

        ActivityLogger::log('delete', "Menghapus thread diskusi: {$discussion->title}");
        $discussion->delete();

        return redirect()->route('guru.discussions.index')->with('success', 'Thread diskusi berhasil dihapus.');
    }

    public function storeComment(Request $request, Discussion $discussion): RedirectResponse
    {
        $this->authorizeCourse($request, $discussion->course_id);

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
