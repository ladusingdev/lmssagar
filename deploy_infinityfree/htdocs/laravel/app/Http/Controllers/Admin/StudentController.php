<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with(['user', 'classRoom', 'department'])
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($q2) => $q2
                ->where('name', 'like', "%{$request->search}%"))
                ->orWhere('nisn', 'like', "%{$request->search}%")
                ->orWhere('nis', 'like', "%{$request->search}%"))
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create(): View
    {
        return view('admin.students.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $student = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => $data['address'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('siswa');

            $student = Student::create([
                'user_id' => $user->id,
                'nisn' => $data['nisn'] ?? null,
                'nis' => $data['nis'] ?? null,
                'class_id' => $data['class_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'parent_name' => $data['parent_name'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
                'admission_year' => $data['admission_year'] ?? null,
                'status' => $data['status'] ?? 'active',
            ]);

            $this->syncEnrollments($student);

            return $student;
        });

        ActivityLogger::log('create', "Menambahkan siswa: {$student->user->name}", $student);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', array_merge(['student' => $student], $this->formData()));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $rules = $this->rules($student);
        $data = $request->validate($rules);

        DB::transaction(function () use ($data, $student, $request) {
            $student->user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => $data['address'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);
            if (! empty($data['password'])) {
                $student->user->password = Hash::make($data['password']);
            }
            $student->user->save();

            $student->update([
                'nisn' => $data['nisn'] ?? null,
                'nis' => $data['nis'] ?? null,
                'class_id' => $data['class_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'parent_name' => $data['parent_name'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
                'admission_year' => $data['admission_year'] ?? null,
                'status' => $data['status'] ?? 'active',
            ]);

            $this->syncEnrollments($student);
        });

        ActivityLogger::log('update', "Memperbarui data siswa: {$student->user->name}", $student);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        ActivityLogger::log('delete', "Menghapus siswa: {$student->user->name}");
        $student->user->delete();

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    private function rules(?Student $student = null): array
    {
        $userId = $student?->user_id;
        $studentId = $student?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$student ? 'nullable' : 'required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'address' => ['nullable', 'string'],
            'nisn' => ['nullable', 'string', Rule::unique('students', 'nisn')->ignore($studentId)],
            'nis' => ['nullable', 'string', Rule::unique('students', 'nis')->ignore($studentId)],
            'class_id' => ['nullable', 'exists:classes,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:20'],
            'admission_year' => ['nullable', 'digits:4'],
            'status' => ['required', 'in:active,graduated,dropout,transferred'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    private function formData(): array
    {
        return [
            'classes' => ClassRoom::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
        ];
    }

    /**
     * Course enrollments are snapshotted when a teaching assignment is created,
     * so a student added to (or moved into) a class afterwards never gets one.
     * Backfill any missing enrollments for the student's current class here.
     */
    private function syncEnrollments(Student $student): void
    {
        if (! $student->class_id) {
            return;
        }

        $existingCourseIds = $student->enrollments()->pluck('course_id');

        $missingCourseIds = Course::where('class_id', $student->class_id)
            ->whereNotIn('id', $existingCourseIds)
            ->pluck('id');

        if ($missingCourseIds->isEmpty()) {
            return;
        }

        $student->enrollments()->createMany(
            $missingCourseIds->map(fn ($courseId) => ['course_id' => $courseId, 'enrolled_at' => now()])->all()
        );

        $this->notifyBackfilledCourses($student, $missingCourseIds);
    }

    /**
     * Assignment/announcement notifications are only fired once, at creation time,
     * to whoever was enrolled then. A student backfilled into a course afterwards
     * would otherwise never see those past notifications, so send catch-up ones.
     */
    private function notifyBackfilledCourses(Student $student, Collection $courseIds): void
    {
        Assignment::whereIn('course_id', $courseIds)
            ->where('is_published', true)
            ->with('course.subject')
            ->each(function (Assignment $assignment) use ($student) {
                $student->user->notify(new GeneralNotification(
                    'Tugas Baru',
                    "Tugas baru \"{$assignment->title}\" telah ditambahkan untuk {$assignment->course->subject->name}.",
                    route('siswa.assignments.show', $assignment)
                ));
            });

        Announcement::whereIn('course_id', $courseIds)
            ->where('is_published', true)
            ->each(function (Announcement $announcement) use ($student) {
                $student->user->notify(new GeneralNotification('Pengumuman Guru', $announcement->title, route('dashboard')));
            });
    }
}
