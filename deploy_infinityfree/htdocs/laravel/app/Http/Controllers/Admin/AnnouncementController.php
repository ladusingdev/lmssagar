<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $announcements = Announcement::query()
            ->with('user')
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('admin.announcements.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $announcement = Announcement::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => 'sekolah',
            'class_id' => $data['class_id'] ?? null,
            'is_published' => $request->boolean('is_published', true),
            'published_at' => now(),
        ]);

        $this->notifyRecipients($announcement);
        ActivityLogger::log('create', "Membuat pengumuman: {$announcement->title}", $announcement);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', array_merge(['announcement' => $announcement], $this->formData()));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $announcement->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'class_id' => $data['class_id'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLogger::log('update', "Memperbarui pengumuman: {$announcement->title}", $announcement);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        ActivityLogger::log('delete', "Menghapus pengumuman: {$announcement->title}");
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    private function notifyRecipients(Announcement $announcement): void
    {
        $recipients = $announcement->class_id
            ? User::whereHas('student', fn ($q) => $q->where('class_id', $announcement->class_id))->get()
            : User::role(['guru', 'siswa'])->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(new GeneralNotification(
                'Pengumuman Baru',
                $announcement->title,
                route('dashboard')
            ));
        }
    }

    private function formData(): array
    {
        return [
            'classes' => ClassRoom::orderBy('name')->get(),
        ];
    }
}
