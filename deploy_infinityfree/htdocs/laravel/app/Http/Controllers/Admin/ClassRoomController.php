<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\Teacher;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassRoomController extends Controller
{
    public function index(Request $request): View
    {
        $classes = ClassRoom::query()
            ->with(['department', 'academicYear', 'homeroomTeacher.user'])
            ->withCount('students')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->department_id, fn ($q) => $q->where('department_id', $request->department_id))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();

        return view('admin.classes.index', compact('classes', 'departments'));
    }

    public function create(): View
    {
        return view('admin.classes.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $class = ClassRoom::create($data);
        ActivityLogger::log('create', "Menambahkan kelas: {$class->name}", $class);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(ClassRoom $class): View
    {
        return view('admin.classes.edit', array_merge(['classRoom' => $class], $this->formData()));
    }

    public function update(Request $request, ClassRoom $class): RedirectResponse
    {
        $data = $this->validated($request);
        $class->update($data);
        ActivityLogger::log('update', "Memperbarui kelas: {$class->name}", $class);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $class): RedirectResponse
    {
        if ($class->students()->exists()) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');
        }

        ActivityLogger::log('delete', "Menghapus kelas: {$class->name}");
        $class->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'in:X,XI,XII'],
            'department_id' => ['required', 'exists:departments,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);
    }

    private function formData(): array
    {
        return [
            'departments' => Department::orderBy('name')->get(),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
            'teachers' => Teacher::with('user')->get(),
        ];
    }
}
