<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $attendances = Attendance::query()
            ->whereHas('schedule.course', fn ($q) => $q->where('teacher_id', $request->user()->teacher->id))
            ->with(['student.user', 'schedule.classRoom', 'schedule.course.subject'])
            ->when($request->date, fn ($q) => $q->where('date', $request->date))
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        return view('guru.attendances.index', compact('attendances'));
    }

    public function create(Request $request): View
    {
        $schedules = Schedule::whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->teacher->id))
            ->with(['classRoom', 'course.subject'])
            ->get();

        $selectedSchedule = null;
        $students = collect();
        $existing = collect();
        $date = $request->date ?? now()->format('Y-m-d');

        if ($request->schedule_id) {
            $selectedSchedule = Schedule::with('classRoom.students.user')->findOrFail($request->schedule_id);
            abort_unless($selectedSchedule->course->teacher_id === $request->user()->teacher->id, 403);
            $students = $selectedSchedule->classRoom->students;
            $existing = Attendance::where('schedule_id', $selectedSchedule->id)->where('date', $date)->get()->keyBy('student_id');
        }

        return view('guru.attendances.create', compact('schedules', 'selectedSchedule', 'students', 'existing', 'date'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:hadir,izin,sakit,alpha'],
        ]);

        $schedule = Schedule::findOrFail($data['schedule_id']);
        abort_unless($schedule->course->teacher_id === $request->user()->teacher->id, 403);

        foreach ($data['status'] as $studentId => $status) {
            Attendance::updateOrCreate(
                ['schedule_id' => $schedule->id, 'student_id' => $studentId, 'date' => $data['date']],
                ['status' => $status, 'recorded_by' => $request->user()->teacher->id, 'note' => $request->input("note.$studentId")]
            );
        }

        ActivityLogger::log('create', "Menginput presensi: {$schedule->classRoom->name} - {$data['date']}");

        return redirect()->route('guru.attendances.index')->with('success', 'Presensi berhasil disimpan.');
    }
}
