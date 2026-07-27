# Analisis Kebutuhan Sistem — LMS SMKN 9 Garut

## 1. Latar Belakang

SMKN 9 Garut membutuhkan sebuah platform digital terpadu untuk mendukung proses belajar-mengajar antara Administrator, Guru, dan Siswa, menggantikan proses manual (distribusi materi fisik, presensi kertas, rekap nilai manual) dengan sistem berbasis web yang dapat diakses kapan saja.

## 2. Tujuan Sistem

1. Menyediakan satu platform terpusat untuk distribusi materi pembelajaran, tugas, kuis, dan ujian online.
2. Mengotomatiskan proses penilaian objektif (pilihan ganda) dan mempermudah penilaian subjektif (essay).
3. Menyediakan rekap presensi dan nilai yang dapat diakses real-time oleh siswa, guru, dan administrator.
4. Menyediakan laporan akademik yang dapat dicetak/diekspor untuk kebutuhan administratif dan akreditasi.
5. Menerapkan kontrol akses berbasis peran (RBAC) sehingga setiap pengguna hanya dapat mengakses data sesuai kewenangannya.

## 3. Kebutuhan Fungsional

### 3.1 Administrator
| Kode | Kebutuhan |
|---|---|
| F-A01 | Sistem dapat melakukan autentikasi (login/logout) administrator |
| F-A02 | Sistem menyediakan dashboard ringkasan statistik seluruh sekolah |
| F-A03 | Admin dapat mengelola data User, Guru, Siswa, Jurusan, Kelas, Mata Pelajaran, Tahun Ajaran |
| F-A04 | Admin dapat mengelola penugasan mengajar (guru ↔ mapel ↔ kelas ↔ tahun ajaran) |
| F-A05 | Admin dapat mengelola seluruh materi, tugas, kuis, ujian, nilai, presensi, dan jadwal di semua kelas |
| F-A06 | Admin dapat membuat pengumuman sekolah dan memoderasi forum diskusi |
| F-A07 | Admin dapat mencetak/export laporan (Guru, Siswa, Nilai, Presensi, Materi, Tugas, Ujian) ke PDF dan Excel |
| F-A08 | Admin dapat melihat log aktivitas seluruh pengguna |
| F-A09 | Admin dapat mengatur konfigurasi sistem (identitas sekolah, logo) |
| F-A10 | Admin dapat melakukan backup dan restore database |

### 3.2 Guru
| Kode | Kebutuhan |
|---|---|
| F-G01 | Guru dapat login dan melihat dashboard aktivitas mengajarnya |
| F-G02 | Guru dapat melihat mata pelajaran dan kelas yang diampu |
| F-G03 | Guru dapat mengunggah materi (PDF, Word, PPT, Video, Gambar) |
| F-G04 | Guru dapat membuat tugas dengan tenggat waktu dan menilai pengumpulan siswa |
| F-G05 | Guru dapat membuat kuis/ujian dengan soal pilihan ganda dan essay, termasuk pengaturan waktu dan acak soal |
| F-G06 | Guru dapat menilai jawaban essay siswa secara manual |
| F-G07 | Guru dapat menginput presensi siswa per sesi jadwal |
| F-G08 | Guru dapat melihat rekap nilai otomatis (tugas, kuis, ujian) per siswa |
| F-G09 | Guru dapat membuat pengumuman untuk kelas yang diampu dan berdiskusi di forum |

### 3.3 Siswa
| Kode | Kebutuhan |
|---|---|
| F-S01 | Siswa dapat login dan melihat dashboard aktivitas belajarnya |
| F-S02 | Siswa dapat melihat dan mengunduh materi pembelajaran |
| F-S03 | Siswa dapat mengumpulkan tugas sebelum/dalam batas toleransi tenggat waktu |
| F-S04 | Siswa dapat mengerjakan kuis/ujian online dengan timer dan auto-submit saat waktu habis |
| F-S05 | Siswa dapat melihat hasil kuis/ujian (skor otomatis untuk pilihan ganda) |
| F-S06 | Siswa dapat melihat rekap nilai, presensi, dan jadwal pelajaran miliknya |
| F-S07 | Siswa dapat berdiskusi di forum dan melihat pengumuman |
| F-S08 | Siswa menerima notifikasi untuk tugas baru, nilai baru, dan pengumuman |

## 4. Kebutuhan Nonfungsional

| Kode | Kategori | Deskripsi |
|---|---|---|
| NF-01 | Usability | Antarmuka responsif (desktop & mobile), navigasi sidebar konsisten di seluruh peran |
| NF-02 | Security | CSRF protection, XSS protection, SQL Injection protection, password hashing, rate limiting login |
| NF-03 | Performance | Query dioptimasi dengan eager loading & index database untuk mendukung ratusan pengguna |
| NF-04 | Reliability | Validasi input di seluruh form, penanganan error dengan flash message |
| NF-05 | Maintainability | Struktur kode mengikuti pola MVC Laravel, penamaan konsisten, terpisah per peran (Admin/Guru/Siswa) |
| NF-06 | Portability | Berjalan di lingkungan XAMPP standar (Apache + MySQL + PHP 8.2+) tanpa dependensi Node.js |
| NF-07 | Auditability | Seluruh aksi penting (create/update/delete/login) tercatat di Activity Log |

## 5. Batasan Sistem

- Registrasi akun publik tidak disediakan; seluruh akun (Guru & Siswa) dibuat oleh Administrator untuk menjaga validitas data akademik.
- Frontend menggunakan Bootstrap 5 dan Vanilla JavaScript tanpa build tool (Vite/NPM) sesuai permintaan proyek.
- Backup/restore database bergantung pada ketersediaan binary `mysqldump`/`mysql` dari instalasi XAMPP di server.
