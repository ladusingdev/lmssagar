<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $student = $request->user()->student;
        $dayName = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][now()->format('l')];

        $activeCourses = $student->enrollments()->where('status', 'active')->count();

        $todaySchedules = Schedule::where('class_id', $student->class_id)
            ->where('day_of_week', $dayName)
            ->with(['course.subject', 'course.teacher.user'])
            ->orderBy('start_time')
            ->get();

        $pendingAssignments = Assignment::where('is_published', true)
            ->whereHas('course.enrollments', fn ($q) => $q->where('student_id', $student->id))
            ->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $student->id))
            ->where('deadline', '>=', now())
            ->with('course.subject')
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        $latestGrades = Grade::where('student_id', $student->id)->with('course.subject')->latest()->limit(5)->get();

        $totalAttendance = Attendance::where('student_id', $student->id)->count();
        $hadirCount = Attendance::where('student_id', $student->id)->where('status', 'hadir')->count();
        $attendancePercentage = $totalAttendance > 0 ? round(($hadirCount / $totalAttendance) * 100, 1) : 0;

        $latestAnnouncements = Announcement::where('is_published', true)
            ->where(function ($q) use ($student) {
                $q->where('type', 'sekolah')->where(fn ($q2) => $q2->whereNull('class_id')->orWhere('class_id', $student->class_id));
                $q->orWhere(fn ($q2) => $q2->where('type', 'guru')->whereHas('course.enrollments', fn ($q3) => $q3->where('student_id', $student->id)));
            })
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('siswa.dashboard', compact(
            'activeCourses', 'todaySchedules', 'pendingAssignments', 'latestGrades', 'attendancePercentage', 'latestAnnouncements'
        ));
    }
}
