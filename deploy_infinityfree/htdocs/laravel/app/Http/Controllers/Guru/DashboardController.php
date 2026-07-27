<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Schedule;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $teacher = $request->user()->teacher;
        $dayName = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][now()->format('l')];

        $todaySchedules = Schedule::whereHas('course', fn ($q) => $q->where('teacher_id', $teacher->id))
            ->where('day_of_week', $dayName)
            ->with(['classRoom', 'course.subject'])
            ->orderBy('start_time')
            ->get();

        $stats = [
            'materials' => Material::where('teacher_id', $teacher->id)->count(),
            'assignments' => $teacher->assignments()->count(),
            'students' => \App\Models\Enrollment::whereHas('course', fn ($q) => $q->where('teacher_id', $teacher->id))->distinct('student_id')->count('student_id'),
        ];

        $recentSubmissions = Submission::whereHas('assignment', fn ($q) => $q->where('teacher_id', $teacher->id))
            ->with(['student.user', 'assignment'])
            ->latest('submitted_at')
            ->limit(8)
            ->get();

        $activityByDay = Submission::whereHas('assignment', fn ($q) => $q->where('teacher_id', $teacher->id))
            ->selectRaw('DATE(submitted_at) as day, count(*) as total')
            ->where('submitted_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        $activityLabels = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));
        $activityData = $activityLabels->map(fn ($day) => $activityByDay[$day] ?? 0);

        return view('guru.dashboard', compact('todaySchedules', 'stats', 'recentSubmissions', 'activityLabels', 'activityData'));
    }
}
