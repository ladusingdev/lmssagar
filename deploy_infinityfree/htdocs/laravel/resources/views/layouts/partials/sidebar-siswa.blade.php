@php($current = request()->route()->getName())
<nav class="nav flex-column pb-4">
    <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ str_starts_with($current, 'siswa.dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-gauge"></i> Dashboard
    </a>

    <div class="nav-section-title">Belajar</div>
    <a href="{{ route('siswa.courses.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.courses') ? 'active' : '' }}"><i class="fa-solid fa-book-open"></i> Mata Pelajaran</a>
    <a href="{{ route('siswa.materials.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.materials') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i> Materi Pembelajaran</a>
    <a href="{{ route('siswa.assignments.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.assignments') ? 'active' : '' }}"><i class="fa-solid fa-pen-to-square"></i> Tugas</a>
    <a href="{{ route('siswa.quizzes.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.quizzes') ? 'active' : '' }}"><i class="fa-solid fa-list-check"></i> Kuis</a>
    <a href="{{ route('siswa.exams.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.exams') ? 'active' : '' }}"><i class="fa-solid fa-file-shield"></i> Ujian Online</a>

    <div class="nav-section-title">Perkembangan</div>
    <a href="{{ route('siswa.grades.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.grades') ? 'active' : '' }}"><i class="fa-solid fa-chart-simple"></i> Nilai</a>
    <a href="{{ route('siswa.attendances.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.attendances') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-check"></i> Presensi</a>
    <a href="{{ route('siswa.schedules.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.schedules') ? 'active' : '' }}"><i class="fa-solid fa-clock"></i> Jadwal Pelajaran</a>

    <div class="nav-section-title">Interaksi</div>
    <a href="{{ route('siswa.discussions.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.discussions') ? 'active' : '' }}"><i class="fa-solid fa-comments"></i> Forum Diskusi</a>
    <a href="{{ route('siswa.announcements.index') }}" class="nav-link {{ str_starts_with($current, 'siswa.announcements') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
</nav>
