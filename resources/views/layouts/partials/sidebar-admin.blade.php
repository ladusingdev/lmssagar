@php($current = request()->route()->getName())
<nav class="nav flex-column pb-4">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ str_starts_with($current, 'admin.dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-gauge"></i> Dashboard
    </a>

    <div class="nav-section-title">Akademik</div>
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ str_starts_with($current, 'admin.users') ? 'active' : '' }}"><i class="fa-solid fa-users-gear"></i> Manajemen User</a>
    <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ str_starts_with($current, 'admin.teachers') ? 'active' : '' }}"><i class="fa-solid fa-chalkboard-user"></i> Manajemen Guru</a>
    <a href="{{ route('admin.students.index') }}" class="nav-link {{ str_starts_with($current, 'admin.students') ? 'active' : '' }}"><i class="fa-solid fa-user-graduate"></i> Manajemen Siswa</a>
    <a href="{{ route('admin.departments.index') }}" class="nav-link {{ str_starts_with($current, 'admin.departments') ? 'active' : '' }}"><i class="fa-solid fa-sitemap"></i> Manajemen Jurusan</a>
    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ str_starts_with($current, 'admin.classes') ? 'active' : '' }}"><i class="fa-solid fa-school"></i> Manajemen Kelas</a>
    <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ str_starts_with($current, 'admin.subjects') ? 'active' : '' }}"><i class="fa-solid fa-book"></i> Mata Pelajaran</a>
    <a href="{{ route('admin.academic-years.index') }}" class="nav-link {{ str_starts_with($current, 'admin.academic-years') ? 'active' : '' }}"><i class="fa-solid fa-calendar-days"></i> Tahun Ajaran</a>
    <a href="{{ route('admin.courses.index') }}" class="nav-link {{ str_starts_with($current, 'admin.courses') ? 'active' : '' }}"><i class="fa-solid fa-diagram-project"></i> Penugasan Mengajar</a>

    <div class="nav-section-title">Pembelajaran</div>
    <a href="{{ route('admin.materials.index') }}" class="nav-link {{ str_starts_with($current, 'admin.materials') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i> Materi</a>
    <a href="{{ route('admin.assignments.index') }}" class="nav-link {{ str_starts_with($current, 'admin.assignments') ? 'active' : '' }}"><i class="fa-solid fa-pen-to-square"></i> Tugas</a>
    <a href="{{ route('admin.quizzes.index') }}" class="nav-link {{ str_starts_with($current, 'admin.quizzes') ? 'active' : '' }}"><i class="fa-solid fa-list-check"></i> Kuis</a>
    <a href="{{ route('admin.exams.index') }}" class="nav-link {{ str_starts_with($current, 'admin.exams') ? 'active' : '' }}"><i class="fa-solid fa-file-shield"></i> Ujian Online</a>
    <a href="{{ route('admin.grades.index') }}" class="nav-link {{ str_starts_with($current, 'admin.grades') ? 'active' : '' }}"><i class="fa-solid fa-chart-simple"></i> Nilai</a>
    <a href="{{ route('admin.attendances.index') }}" class="nav-link {{ str_starts_with($current, 'admin.attendances') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-check"></i> Presensi</a>
    <a href="{{ route('admin.schedules.index') }}" class="nav-link {{ str_starts_with($current, 'admin.schedules') ? 'active' : '' }}"><i class="fa-solid fa-clock"></i> Jadwal</a>

    <div class="nav-section-title">Interaksi</div>
    <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ str_starts_with($current, 'admin.announcements') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
    <a href="{{ route('admin.discussions.index') }}" class="nav-link {{ str_starts_with($current, 'admin.discussions') ? 'active' : '' }}"><i class="fa-solid fa-comments"></i> Forum Diskusi</a>

    <div class="nav-section-title">Sistem</div>
    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ str_starts_with($current, 'admin.reports') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Laporan</a>
    <a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ str_starts_with($current, 'admin.activity-logs') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i> Activity Log</a>
    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ str_starts_with($current, 'admin.settings') ? 'active' : '' }}"><i class="fa-solid fa-gears"></i> Pengaturan Sistem</a>
    <a href="{{ route('admin.backup.index') }}" class="nav-link {{ str_starts_with($current, 'admin.backup') ? 'active' : '' }}"><i class="fa-solid fa-database"></i> Backup &amp; Restore</a>
</nav>
