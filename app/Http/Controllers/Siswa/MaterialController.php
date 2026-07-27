<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $materials = Material::query()
            ->where('is_published', true)
            ->whereHas('course.enrollments', fn ($q) => $q->where('student_id', $studentId))
            ->with(['course.subject', 'teacher.user'])
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('siswa.materials.index', compact('materials'));
    }

    public function show(Request $request, Material $material): View
    {
        $this->authorizeAccess($request, $material);

        return view('siswa.materials.show', compact('material'));
    }

    public function download(Request $request, Material $material): StreamedResponse
    {
        $this->authorizeAccess($request, $material);
        abort_unless($material->file_path, 404);

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }

    private function authorizeAccess(Request $request, Material $material): void
    {
        $studentId = $request->user()->student->id;
        abort_unless(
            $material->is_published && $material->course->enrollments()->where('student_id', $studentId)->exists(),
            403
        );
    }
}
