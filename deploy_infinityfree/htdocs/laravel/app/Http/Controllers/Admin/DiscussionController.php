<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\DiscussionComment;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Request $request): View
    {
        $discussions = Discussion::query()
            ->with(['course.subject', 'user'])
            ->withCount('allComments')
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.discussions.index', compact('discussions'));
    }

    public function show(Discussion $discussion): View
    {
        $discussion->load(['comments.user', 'comments.replies.user', 'course.subject']);

        return view('admin.discussions.show', compact('discussion'));
    }

    public function destroy(Discussion $discussion): RedirectResponse
    {
        ActivityLogger::log('delete', "Menghapus thread diskusi: {$discussion->title}");
        $discussion->delete();

        return redirect()->route('admin.discussions.index')->with('success', 'Thread diskusi berhasil dihapus.');
    }

    public function destroyComment(Discussion $discussion, DiscussionComment $comment): RedirectResponse
    {
        $comment->delete();
        ActivityLogger::log('delete', 'Menghapus komentar diskusi');

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
