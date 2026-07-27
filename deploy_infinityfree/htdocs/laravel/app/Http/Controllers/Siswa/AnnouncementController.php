<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->student;

        $announcements = Announcement::query()
            ->where('is_published', true)
            ->where(function ($q) use ($student) {
                $q->where('type', 'sekolah')
                    ->where(fn ($q2) => $q2->whereNull('class_id')->orWhere('class_id', $student->class_id));
                $q->orWhere(function ($q2) use ($student) {
                    $q2->where('type', 'guru')->whereHas('course.enrollments', fn ($q3) => $q3->where('student_id', $student->id));
                });
            })
            ->with('user')
            ->latest('published_at')
            ->paginate(15);

        return view('siswa.announcements.index', compact('announcements'));
    }
}
