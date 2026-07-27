<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = auth()->user();

        return match (true) {
            $user->isAdmin() => redirect()->route('admin.dashboard'),
            $user->isTeacher() => redirect()->route('guru.dashboard'),
            $user->isStudent() => redirect()->route('siswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
