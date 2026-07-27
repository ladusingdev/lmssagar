<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends Controller
{
    private const ALLOWED_MIMES = 'pdf,doc,docx,ppt,pptx,mp4,jpg,jpeg,png';

    public function index(Request $request): View
    {
        $materials = Material::query()
            ->where('teacher_id', $request->user()->teacher->id)
            ->with(['course.subject', 'course.classRoom'])
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('guru.materials.index', compact('materials'));
    }

    public function create(Request $request): View
    {
        return view('guru.materials.create', ['courses' => $this->myCourses($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:pdf,word,ppt,video,image,link'],
            'file' => ['required_unless:type,link', 'nullable', 'file', 'mimes:'.self::ALLOWED_MIMES, 'max:20480'],
            'video_url' => ['required_if:type,link', 'nullable', 'url'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $this->authorizeCourse($request, $data['course_id']);

        $material = new Material([
            'course_id' => $data['course_id'],
            'teacher_id' => $request->user()->teacher->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'video_url' => $data['video_url'] ?? null,
            'is_published' => $request->boolean('is_published', true),
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $material->file_path = $file->store('materials', 'public');
            $material->file_name = $file->getClientOriginalName();
            $material->file_size = $file->getSize();
        }

        $material->save();
        ActivityLogger::log('create', "Menambahkan materi: {$material->title}", $material);

        return redirect()->route('guru.materials.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Request $request, Material $material): View
    {
        abort_unless($material->teacher_id === $request->user()->teacher->id, 403);

        return view('guru.materials.edit', ['material' => $material, 'courses' => $this->myCourses($request)]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        abort_unless($material->teacher_id === $request->user()->teacher->id, 403);

        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:pdf,word,ppt,video,image,link'],
            'file' => ['nullable', 'file', 'mimes:'.self::ALLOWED_MIMES, 'max:20480'],
            'video_url' => ['nullable', 'url'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $this->authorizeCourse($request, $data['course_id']);

        $material->fill([
            'course_id' => $data['course_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'video_url' => $data['video_url'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('file')) {
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $file = $request->file('file');
            $material->file_path = $file->store('materials', 'public');
            $material->file_name = $file->getClientOriginalName();
            $material->file_size = $file->getSize();
        }

        $material->save();
        ActivityLogger::log('update', "Memperbarui materi: {$material->title}", $material);

        return redirect()->route('guru.materials.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Request $request, Material $material): RedirectResponse
    {
        abort_unless($material->teacher_id === $request->user()->teacher->id, 403);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        ActivityLogger::log('delete', "Menghapus materi: {$material->title}");
        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus.');
    }

    public function download(Request $request, Material $material): StreamedResponse
    {
        abort_unless($material->file_path, 404);

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }

    private function myCourses(Request $request)
    {
        return Course::where('teacher_id', $request->user()->teacher->id)->with(['subject', 'classRoom'])->get();
    }

    private function authorizeCourse(Request $request, int $courseId): void
    {
        abort_unless(
            Course::where('id', $courseId)->where('teacher_id', $request->user()->teacher->id)->exists(),
            403
        );
    }
}
