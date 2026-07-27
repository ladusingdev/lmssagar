<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $schedules = Schedule::query()
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->teacher->id))
            ->with(['classRoom', 'course.subject'])
            ->orderByRaw("FIELD(day_of_week, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('guru.schedules.index', compact('schedules'));
    }
}
