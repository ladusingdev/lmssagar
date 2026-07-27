<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Material;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'teachers' => Teacher::count(),
            'students' => Student::where('status', 'active')->count(),
            'classes' => ClassRoom::count(),
            'subjects' => Subject::count(),
            'materials' => Material::count(),
            'assignments' => Assignment::count(),
            'exams' => Exam::count(),
        ];

        $attendanceRecap = Attendance::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $gradeAverage = round(Grade::avg('final_score') ?? 0, 1);

        $activityByDay = ActivityLog::selectRaw('DATE(created_at) as day, count(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        $activityLabels = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));
        $activityData = $activityLabels->map(fn ($day) => $activityByDay[$day] ?? 0);

        $recentActivities = ActivityLog::with('user')->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'attendanceRecap', 'gradeAverage', 'activityLabels', 'activityData', 'recentActivities'));
    }
}
