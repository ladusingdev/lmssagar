<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Notifications\GeneralNotification;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $announcements = Announcement::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('guru.announcements.index', compact('announcements'));
    }

    public function create(Request $request): View
    {
        return view('guru.announcements.create', ['courses' => $this->myCourses($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'course_id' => ['required', 'exists:courses,id'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $this->authorizeCourse($request, $data['course_id']);
        $course = Course::with('students.user')->find($data['course_id']);

        $announcement = Announcement::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => 'guru',
            'course_id' => $data['course_id'],
            'class_id' => $course->class_id,
            'is_published' => $request->boolean('is_published', true),
            'published_at' => now(),
        ]);

        foreach ($course->students as $student) {
            $student->user->notify(new GeneralNotification('Pengumuman Guru', $announcement->title, route('dashboard')));
        }

        ActivityLogger::log('create', "Membuat pengumuman: {$announcement->title}", $announcement);

        return redirect()->route('guru.announcements.index')->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function edit(Request $request, Announcement $announcement): View
    {
        abort_unless($announcement->user_id === $request->user()->id, 403);

        return view('guru.announcements.edit', ['announcement' => $announcement, 'courses' => $this->myCourses($request)]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        abort_unless($announcement->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'course_id' => ['required', 'exists:courses,id'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $this->authorizeCourse($request, $data['course_id']);
        $course = Course::find($data['course_id']);

        $announcement->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'course_id' => $data['course_id'],
            'class_id' => $course->class_id,
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLogger::log('update', "Memperbarui pengumuman: {$announcement->title}", $announcement);

        return redirect()->route('guru.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        abort_unless($announcement->user_id === $request->user()->id, 403);

        ActivityLogger::log('delete', "Menghapus pengumuman: {$announcement->title}");
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
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
