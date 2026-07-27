<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $departments = Department::query()
            ->withCount(['classes', 'students'])
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('code', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('admin.departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
        ]);

        $department = Department::create($data);
        ActivityLogger::log('create', "Menambahkan jurusan: {$department->name}", $department);

        return redirect()->route('admin.departments.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(Department $department): View
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($department->id)],
            'description' => ['nullable', 'string'],
        ]);

        $department->update($data);
        ActivityLogger::log('update', "Memperbarui jurusan: {$department->name}", $department);

        return redirect()->route('admin.departments.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->classes()->exists() || $department->students()->exists()) {
            return back()->with('error', 'Jurusan tidak dapat dihapus karena masih memiliki data kelas/siswa terkait.');
        }

        ActivityLogger::log('delete', "Menghapus jurusan: {$department->name}");
        $department->delete();

        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
