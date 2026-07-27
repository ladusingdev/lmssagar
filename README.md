# LMS SMKN 9 Garut — Learning Management System

Aplikasi Learning Management System (LMS) berbasis web untuk mendukung proses pembelajaran digital di SMKN 9 Garut, dibangun dengan **Laravel 12**. Aplikasi ini menghubungkan tiga peran utama — **Administrator**, **Guru**, dan **Siswa** — dalam satu sistem terpadu untuk manajemen akademik, distribusi materi, tugas, kuis, ujian online, presensi, nilai, dan komunikasi sekolah.

Dokumentasi tambahan tersedia di folder [`docs/`](docs/):

- [`docs/ANALISIS_KEBUTUHAN.md`](docs/ANALISIS_KEBUTUHAN.md) — Analisis kebutuhan sistem, kebutuhan fungsional & nonfungsional
- [`docs/DIAGRAM.md`](docs/DIAGRAM.md) — Use Case Diagram, Activity Diagram, Sequence Diagram
- [`docs/ERD.md`](docs/ERD.md) — Entity Relationship Diagram dan struktur database lengkap
- [`docs/INSTALLATION.md`](docs/INSTALLATION.md) — Panduan instalasi, konfigurasi `.env`, menjalankan aplikasi, dan deployment

---

## 1. Ringkasan Fitur

### Administrator
Login, Dashboard, Manajemen User/Guru/Siswa/Jurusan/Kelas/Mata Pelajaran/Tahun Ajaran, Manajemen Materi/Tugas/Kuis/Ujian Online/Nilai/Presensi/Jadwal, Pengumuman, Forum Diskusi, Laporan (cetak/PDF/Excel), Activity Log, Pengaturan Sistem, Backup & Restore Database, Profil, Ganti Password.

### Guru
Login, Dashboard, Mata Pelajaran Saya, Kelas Saya, Upload Materi (PDF/Word/PPT/Video/Gambar), Membuat & Menilai Tugas, Membuat Kuis & Ujian Online (Pilihan Ganda & Essay), Presensi, Nilai, Pengumuman, Forum Diskusi, Jadwal Mengajar, Profil, Ganti Password.

### Siswa
Login, Dashboard, Mata Pelajaran, Materi Pembelajaran (lihat/unduh/putar video), Mengerjakan & Mengumpulkan Tugas, Mengikuti Kuis & Ujian Online (timer, auto-submit, auto-scoring), Melihat Nilai & Presensi, Jadwal Pelajaran, Forum Diskusi, Pengumuman, Notifikasi, Profil, Ganti Password.

---

## 2. Spesifikasi Teknis

| Komponen | Teknologi |
|---|---|
| Framework | Laravel 12 |
| Bahasa | PHP 8.2+ |
| Database | MySQL / MariaDB |
| Template Engine | Blade |
| ORM | Eloquent |
| Frontend | HTML5, CSS3, Bootstrap 5, Vanilla JavaScript |
| Ikon | Font Awesome 6 |
| Grafik | Chart.js |
| Autentikasi | Laravel Fortify |
| Otorisasi (RBAC) | Spatie Laravel-Permission |
| Export PDF | barryvdh/laravel-dompdf |
| Export Excel | maatwebsite/excel |
| Server Lokal | XAMPP (Apache + MySQL) |

> **Catatan:** Proyek ini sengaja **tidak menggunakan Node.js/NPM/Vite**. Bootstrap 5, Font Awesome, dan Chart.js sudah **di-vendor secara lokal** ke dalam `public/vendor/` (bukan dimuat dari CDN), sedangkan CSS/JS kustom disajikan langsung dari folder `public/`. Dengan begitu aplikasi bisa berjalan **100% offline** tanpa koneksi internet sama sekali, sekaligus tanpa proses build tambahan — cocok untuk pengguna yang tidak familier dengan tooling frontend modern maupun untuk deployment di lingkungan tanpa akses internet.

### Tema Warna

| Warna | Kode Hex | Kegunaan |
|---|---|---|
| Dark Blue | `#0F172A` | Warna utama (sidebar, navbar, teks penting) |
| Orange | `#F97316` | Warna aksen (tombol, badge aktif, highlight) |
| White | `#FFFFFF` | Latar kartu/konten |
| Light Gray | `#F8FAFC` | Latar halaman |

---

## 3. Instalasi Cepat (Quick Start)

Panduan lengkap ada di [`docs/INSTALLATION.md`](docs/INSTALLATION.md). Ringkasan untuk pengguna XAMPP di Windows:

```powershell
# 1. Salin proyek ke folder htdocs XAMPP (misal: C:\xampp\htdocs\LMS9), lalu masuk ke foldernya
cd C:\xampp\htdocs\LMS9

# 2. Install dependency PHP
composer install

# 3. Salin file environment lalu sesuaikan
copy .env.example .env
php artisan key:generate

# 4. Buat database "lms_smkn9garut" via phpMyAdmin, ATAU import langsung file database/lms_smkn9garut.sql

# 5. Jalankan migrasi + seeder (lewati langkah ini jika mengimpor lms_smkn9garut.sql)
php artisan migrate --seed

# 6. Buat symbolic link storage (untuk upload materi/tugas/avatar)
php artisan storage:link

# 7. Jalankan aplikasi
php artisan serve
```

Buka `http://127.0.0.1:8000` (atau `http://localhost/LMS9/public` jika menjalankan lewat Apache XAMPP).

### Akun Login Contoh

Semua akun hasil seeder menggunakan password: **`password`**

| Peran | Email |
|---|---|
| Administrator | `admin@smkn9garut.sch.id` |
| Guru | `guru1@smkn9garut.sch.id` s.d. `guru10@smkn9garut.sch.id` |
| Siswa | `siswa1@smkn9garut.sch.id` s.d. `siswa100@smkn9garut.sch.id` |

---

## 4. Struktur Folder Utama

```
LMS9/
├── app/
│   ├── Actions/Fortify/       # Kustomisasi aksi autentikasi (profil, password)
│   ├── Exports/               # Kelas export Excel (Maatwebsite\Excel)
│   ├── Http/Controllers/
│   │   ├── Admin/             # Controller khusus peran Administrator
│   │   ├── Guru/              # Controller khusus peran Guru
│   │   ├── Siswa/             # Controller khusus peran Siswa
│   │   └── *.php              # Controller bersama (Dashboard, Profile, Notification)
│   ├── Models/                # Model Eloquent (User, Teacher, Student, Course, dst.)
│   ├── Notifications/         # Notifikasi database (GeneralNotification)
│   ├── Providers/              # Service provider (Fortify, App)
│   └── Services/              # Logika bisnis (GradeService, ActivityLogger)
├── bootstrap/
│   ├── app.php                # Konfigurasi middleware & routing
│   └── providers.php          # Daftar service provider aktif
├── config/                    # File konfigurasi (fortify, permission, dompdf, excel, backup)
├── database/
│   ├── factories/             # Factory untuk data uji/seeder
│   ├── migrations/            # Skema database (28+ tabel)
│   ├── seeders/               # Seeder data contoh
│   └── lms_smkn9garut.sql     # Dump database siap import phpMyAdmin
├── public/
│   ├── vendor/                 # Bootstrap, Font Awesome, Chart.js (di-vendor lokal, bukan CDN)
│   ├── css/app.css            # Style kustom (tema Dark Blue/Orange)
│   └── js/app.js              # Script kustom (sidebar, timer ujian, konfirmasi hapus)
├── resources/views/
│   ├── layouts/                # Layout utama + partial sidebar per-peran
│   ├── auth/                  # Halaman login, lupa password, verifikasi email
│   ├── admin/ guru/ siswa/    # View per modul per peran
│   └── profile/ notifications/
├── routes/
│   ├── web.php                # Route bersama (dashboard, profil, notifikasi)
│   ├── admin.php               # Route khusus Administrator
│   ├── guru.php                # Route khusus Guru
│   └── siswa.php               # Route khusus Siswa
└── docs/                      # Dokumentasi lengkap proyek
```

---

## 5. Keamanan

- **CSRF Protection** — token CSRF pada seluruh form (`@csrf`), divalidasi middleware bawaan Laravel.
- **SQL Injection Protection** — seluruh query menggunakan Eloquent ORM / Query Builder dengan parameter binding.
- **XSS Protection** — Blade escaping otomatis (`{{ }}`) pada seluruh output ke halaman.
- **Password Hashing** — bcrypt via `Hash::make()` / cast `password => hashed`.
- **Secure File Upload** — validasi ekstensi (`mimes:`) dan ukuran maksimum pada setiap upload (materi, tugas, avatar, backup).
- **Session Protection** — sesi tersimpan di database, regenerasi token saat login.
- **Rate Limiting** — percobaan login dibatasi 5x/menit per kombinasi email+IP (Laravel Fortify).
- **Role-Based Access Control (RBAC)** — middleware `role:` (Spatie Permission) pada seluruh grup route Admin/Guru/Siswa, ditambah pengecekan kepemilikan data (mis. guru hanya bisa mengelola mata pelajaran miliknya sendiri).

---

## 6. Pengujian

Aplikasi telah diuji secara manual end-to-end mencakup:

- Alur login/logout untuk ketiga peran dan proteksi middleware role.
- Seluruh halaman CRUD Admin (Users, Teachers, Students, Departments, Classes, Subjects, Academic Years, Courses, Materials, Assignments, Quizzes, Exams, Grades, Attendances, Schedules, Announcements, Discussions).
- Export laporan PDF (DomPDF) dan Excel (Maatwebsite\Excel) untuk 7 jenis laporan.
- Alur pengerjaan Kuis oleh siswa: mulai → kerjakan (timer) → auto-save jawaban → submit → auto-scoring → lihat hasil.
- Dashboard tiga peran dengan statistik real-time dan grafik Chart.js.

Jalankan test suite otomatis (PHPUnit) dengan:

```bash
php artisan test
```

---

## 7. Lisensi

Proyek ini dibangun di atas framework [Laravel](https://laravel.com) yang berlisensi [MIT](https://opensource.org/licenses/MIT). Kode aplikasi LMS SMKN 9 Garut disusun untuk keperluan akademik/skripsi dan dapat dikembangkan lebih lanjut sesuai kebutuhan.
