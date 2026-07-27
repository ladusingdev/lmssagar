<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Schedule;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $schedules = Schedule::query()
            ->with(['classRoom', 'course.subject', 'course.teacher.user', 'academicYear'])
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->orderByRaw("FIELD(day_of_week, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.schedules.index', compact('schedules', 'classes'));
    }

    public function create(): View
    {
        return view('admin.schedules.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $schedule = Schedule::create($data);
        ActivityLogger::log('create', "Menambahkan jadwal: {$schedule->classRoom->name}", $schedule);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View
    {
        return view('admin.schedules.edit', array_merge(['schedule' => $schedule], $this->formData()));
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $schedule->update($this->validated($request));
        ActivityLogger::log('update', "Memperbarui jadwal: {$schedule->classRoom->name}", $schedule);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        ActivityLogger::log('delete', 'Menghapus jadwal');
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'day_of_week' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:50'],
        ]);
    }

    private function formData(): array
    {
        return [
            'classes' => ClassRoom::orderBy('name')->get(),
            'courses' => Course::with(['subject', 'classRoom', 'teacher.user'])->get(),
            'academicYears' => \App\Models\AcademicYear::orderByDesc('start_date')->get(),
        ];
    }
}
