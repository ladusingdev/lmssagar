<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $teachers = Teacher::query()
            ->with('user')
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($q2) => $q2
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
            )->orWhere('nip', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($q) => $q->where('employment_status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'address' => ['nullable', 'string'],
            'nip' => ['nullable', 'string', 'unique:teachers,nip'],
            'nuptk' => ['nullable', 'string', 'unique:teachers,nuptk'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'employment_status' => ['required', 'in:PNS,PPPK,Honorer,GTT,Kontrak'],
        ]);

        $teacher = DB::transaction(function () use ($data) {
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
            $user->assignRole('guru');

            return Teacher::create([
                'user_id' => $user->id,
                'nip' => $data['nip'] ?? null,
                'nuptk' => $data['nuptk'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'employment_status' => $data['employment_status'],
            ]);
        });

        ActivityLogger::log('create', "Menambahkan guru: {$teacher->user->name}", $teacher);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher): View
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teacher->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'address' => ['nullable', 'string'],
            'nip' => ['nullable', 'string', Rule::unique('teachers', 'nip')->ignore($teacher->id)],
            'nuptk' => ['nullable', 'string', Rule::unique('teachers', 'nuptk')->ignore($teacher->id)],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'employment_status' => ['required', 'in:PNS,PPPK,Honorer,GTT,Kontrak'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data, $teacher, $request) {
            $teacher->user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => $data['address'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);
            if (! empty($data['password'])) {
                $teacher->user->password = Hash::make($data['password']);
            }
            $teacher->user->save();

            $teacher->update([
                'nip' => $data['nip'] ?? null,
                'nuptk' => $data['nuptk'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'employment_status' => $data['employment_status'],
            ]);
        });

        ActivityLogger::log('update', "Memperbarui data guru: {$teacher->user->name}", $teacher);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        if ($teacher->courses()->exists()) {
            return back()->with('error', 'Guru tidak dapat dihapus karena masih mengampu mata pelajaran. Pindahkan atau hapus mata pelajaran tersebut terlebih dahulu.');
        }

        DB::transaction(function () use ($teacher) {
            ActivityLogger::log('delete', "Menghapus guru: {$teacher->user->name}");
            $teacher->user->delete();
        });

        return back()->with('success', 'Data guru berhasil dihapus.');
    }
}
