@php($current = request()->route()->getName())
<nav class="nav flex-column pb-4">
    <a href="{{ route('guru.dashboard') }}" class="nav-link {{ str_starts_with($current, 'guru.dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-gauge"></i> Dashboard
    </a>

    <div class="nav-section-title">Mengajar</div>
    <a href="{{ route('guru.courses.index') }}" class="nav-link {{ str_starts_with($current, 'guru.courses') ? 'active' : '' }}"><i class="fa-solid fa-book-open"></i> Mata Pelajaran Saya</a>
    <a href="{{ route('guru.schedules.index') }}" class="nav-link {{ str_starts_with($current, 'guru.schedules') ? 'active' : '' }}"><i class="fa-solid fa-clock"></i> Jadwal Mengajar</a>

    <div class="nav-section-title">Pembelajaran</div>
    <a href="{{ route('guru.materials.index') }}" class="nav-link {{ str_starts_with($current, 'guru.materials') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i> Materi</a>
    <a href="{{ route('guru.assignments.index') }}" class="nav-link {{ str_starts_with($current, 'guru.assignments') ? 'active' : '' }}"><i class="fa-solid fa-pen-to-square"></i> Tugas</a>
    <a href="{{ route('guru.quizzes.index') }}" class="nav-link {{ str_starts_with($current, 'guru.quizzes') ? 'active' : '' }}"><i class="fa-solid fa-list-check"></i> Kuis</a>
    <a href="{{ route('guru.exams.index') }}" class="nav-link {{ str_starts_with($current, 'guru.exams') ? 'active' : '' }}"><i class="fa-solid fa-file-shield"></i> Ujian Online</a>
    <a href="{{ route('guru.attendances.index') }}" class="nav-link {{ str_starts_with($current, 'guru.attendances') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-check"></i> Presensi</a>
    <a href="{{ route('guru.grades.index') }}" class="nav-link {{ str_starts_with($current, 'guru.grades') ? 'active' : '' }}"><i class="fa-solid fa-chart-simple"></i> Nilai</a>

    <div class="nav-section-title">Interaksi</div>
    <a href="{{ route('guru.announcements.index') }}" class="nav-link {{ str_starts_with($current, 'guru.announcements') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
    <a href="{{ route('guru.discussions.index') }}" class="nav-link {{ str_starts_with($current, 'guru.discussions') ? 'active' : '' }}"><i class="fa-solid fa-comments"></i> Forum Diskusi</a>
</nav>
