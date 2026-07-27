<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $attendances = Attendance::where('student_id', $studentId)
            ->with(['schedule.course.subject'])
            ->latest('date')
            ->paginate(20);

        $recap = Attendance::where('student_id', $studentId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalRecords = $recap->sum();
        $percentageHadir = $totalRecords > 0 ? round((($recap['hadir'] ?? 0) / $totalRecords) * 100, 1) : 0;

        return view('siswa.attendances.index', compact('attendances', 'recap', 'percentageHadir'));
    }
}
