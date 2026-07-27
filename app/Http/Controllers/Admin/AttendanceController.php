<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $attendances = Attendance::query()
            ->with(['student.user', 'schedule.classRoom', 'schedule.course.subject'])
            ->when($request->class_id, fn ($q) => $q->whereHas('schedule', fn ($q2) => $q2->where('class_id', $request->class_id)))
            ->when($request->date, fn ($q) => $q->where('date', $request->date))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.attendances.index', compact('attendances', 'classes'));
    }

    public function edit(Attendance $attendance): View
    {
        return view('admin.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:hadir,izin,sakit,alpha'],
            'note' => ['nullable', 'string'],
        ]);

        $attendance->update($data);
        ActivityLogger::log('update', "Memperbarui presensi: {$attendance->student->user->name}", $attendance);

        return redirect()->route('admin.attendances.index')->with('success', 'Presensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        ActivityLogger::log('delete', "Menghapus presensi: {$attendance->student->user->name}");
        $attendance->delete();

        return back()->with('success', 'Presensi berhasil dihapus.');
    }
}
