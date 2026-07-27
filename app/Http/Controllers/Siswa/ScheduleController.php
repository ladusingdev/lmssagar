<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $classId = $request->user()->student->class_id;

        $schedules = Schedule::where('class_id', $classId)
            ->with(['course.subject', 'course.teacher.user'])
            ->orderByRaw("FIELD(day_of_week, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('siswa.schedules.index', compact('schedules'));
    }
}
