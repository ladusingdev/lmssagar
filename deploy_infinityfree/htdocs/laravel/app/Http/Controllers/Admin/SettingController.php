<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'school_address' => ['nullable', 'string'],
            'school_phone' => ['nullable', 'string', 'max:20'],
            'school_email' => ['nullable', 'email'],
            'principal_name' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        foreach (['school_name', 'school_address', 'school_phone', 'school_email', 'principal_name'] as $key) {
            Setting::set($key, $data[$key] ?? null, 'school');
        }

        if ($request->hasFile('logo')) {
            Setting::set('school_logo', $request->file('logo')->store('settings', 'public'), 'school');
        }

        ActivityLogger::log('update', 'Memperbarui pengaturan sistem');

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
