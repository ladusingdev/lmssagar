# BAB IV HASIL DAN PEMBAHASAN

Bab ini menyajikan hasil penelitian mengikuti empat tahap **Design and Development Research (DDR)** yang telah diuraikan pada Bab III: Analysis, Design, Development, dan Evaluation.

## 4.1 Hasil Tahap Analysis

### 4.1.1 Gambaran Umum Sistem

Sistem yang dikembangkan adalah **Learning Management System (LMS) SMKN 9 Garut**, sebuah aplikasi web yang menghubungkan tiga peran pengguna — Administrator, Guru, dan Siswa — dalam satu sistem terpadu untuk manajemen akademik, distribusi materi pembelajaran, pengelolaan tugas, kuis, ujian daring, presensi, nilai, jadwal, dan komunikasi sekolah (pengumuman dan forum diskusi).

### 4.1.2 Kebutuhan Fungsional

Berdasarkan hasil observasi dan analisis proses bisnis, dirumuskan kebutuhan fungsional sebagai berikut:

**Administrator**

| Kode | Kebutuhan |
|---|---|
| F-A01 | Sistem dapat melakukan autentikasi (login/logout) administrator |
| F-A02 | Sistem menyediakan dashboard ringkasan statistik seluruh sekolah |
| F-A03 | Admin dapat mengelola data User, Guru, Siswa, Jurusan, Kelas, Mata Pelajaran, Tahun Ajaran |
| F-A04 | Admin dapat mengelola penugasan mengajar (guru ↔ mapel ↔ kelas ↔ tahun ajaran) |
| F-A05 | Admin dapat mengelola seluruh materi, tugas, kuis, ujian, nilai, presensi, dan jadwal di semua kelas |
| F-A06 | Admin dapat membuat pengumuman sekolah dan memoderasi forum diskusi |
| F-A07 | Admin dapat mencetak/ekspor laporan (Guru, Siswa, Nilai, Presensi, Materi, Tugas, Ujian) ke PDF dan Excel |
| F-A08 | Admin dapat melihat log aktivitas seluruh pengguna |
| F-A09 | Admin dapat mengatur konfigurasi sistem (identitas sekolah, logo) |
| F-A10 | Admin dapat melakukan backup dan restore basis data |

**Guru**

| Kode | Kebutuhan |
|---|---|
| F-G01 | Guru dapat login dan melihat dashboard aktivitas mengajarnya |
| F-G02 | Guru dapat melihat mata pelajaran dan kelas yang diampu |
| F-G03 | Guru dapat mengunggah materi (PDF, Word, PPT, Video, Gambar) |
| F-G04 | Guru dapat membuat tugas dengan tenggat waktu dan menilai pengumpulan siswa |
| F-G05 | Guru dapat membuat kuis/ujian dengan soal pilihan ganda dan esai, termasuk pengaturan waktu dan acak soal |
| F-G06 | Guru dapat menilai jawaban esai siswa secara manual |
| F-G07 | Guru dapat menginput presensi siswa per sesi jadwal |
| F-G08 | Guru dapat melihat rekap nilai otomatis (tugas, kuis, ujian) per siswa |
| F-G09 | Guru dapat membuat pengumuman untuk kelas yang diampu dan berdiskusi di forum |

**Siswa**

| Kode | Kebutuhan |
|---|---|
| F-S01 | Siswa dapat login dan melihat dashboard aktivitas belajarnya |
| F-S02 | Siswa dapat melihat dan mengunduh materi pembelajaran |
| F-S03 | Siswa dapat mengumpulkan tugas sebelum/dalam batas toleransi tenggat waktu |
| F-S04 | Siswa dapat mengerjakan kuis/ujian daring dengan timer dan auto-submit saat waktu habis |
| F-S05 | Siswa dapat melihat hasil kuis/ujian (skor otomatis untuk pilihan ganda) |
| F-S06 | Siswa dapat melihat rekap nilai, presensi, dan jadwal pelajaran miliknya |
| F-S07 | Siswa dapat berdiskusi di forum dan melihat pengumuman |
| F-S08 | Siswa menerima notifikasi untuk tugas baru, nilai baru, dan pengumuman |

### 4.1.3 Kebutuhan Nonfungsional

| Kode | Kategori | Deskripsi |
|---|---|---|
| NF-01 | Usability | Antarmuka responsif (desktop & mobile), navigasi sidebar konsisten di seluruh peran |
| NF-02 | Security | Proteksi CSRF, XSS, SQL Injection, hashing password, rate limiting login |
| NF-03 | Performance | Query dioptimasi dengan eager loading & index basis data |
| NF-04 | Reliability | Validasi input pada seluruh form, penanganan error dengan flash message |
| NF-05 | Maintainability | Struktur kode mengikuti pola MVC Laravel, penamaan konsisten, terpisah per peran |
| NF-06 | Portability | Berjalan di lingkungan XAMPP standar (Apache + MySQL + PHP 8.2+) tanpa dependensi Node.js |
| NF-07 | Auditability | Seluruh aksi penting (create/update/delete/login) tercatat di Activity Log |

### 4.1.4 Batasan Sistem

Registrasi akun publik tidak disediakan — seluruh akun Guru dan Siswa dibuat oleh Administrator; frontend menggunakan Bootstrap 5 dan JavaScript murni tanpa build tool (Vite/NPM); serta fitur backup/restore bergantung pada ketersediaan biner `mysqldump`/`mysql` bawaan instalasi XAMPP.

## 4.2 Hasil Tahap Design

### 4.2.1 Arsitektur Sistem

Sistem dibangun mengikuti pola **Model-View-Controller (MVC)** bawaan Laravel, dengan pemisahan direktori *Controller* dan *View* berdasarkan peran pengguna:

```
app/Http/Controllers/
├── Admin/    (24 controller — akses penuh seluruh modul)
├── Guru/     (14 controller — akses terbatas pada mata pelajaran yang diampu)
├── Siswa/    (11 controller — akses terbatas pada data milik sendiri)
└── *.php     (Controller bersama: Dashboard, Profile, Notification)
```

Setiap grup rute (`routes/admin.php`, `routes/guru.php`, `routes/siswa.php`) dilindungi middleware `auth`, `verified`, dan `role:<peran>` dari paket Spatie Laravel-Permission, sehingga permintaan dari pengguna yang tidak memiliki peran sesuai akan ditolak sebelum mencapai logika Controller.

### 4.2.2 Use Case Diagram

Use case diagram memetakan interaksi tiga aktor (Administrator, Guru, Siswa) terhadap 22 fungsi utama sistem, mulai dari autentikasi, pengelolaan data master, pengelolaan materi/tugas/kuis/ujian, presensi, nilai, pengumuman, forum diskusi, laporan, hingga pengaturan sistem. Diagram lengkap dalam notasi Mermaid tersedia pada `docs/DIAGRAM.md` bagian 1, dengan ringkasan pemetaan aktor sebagai berikut:

| Aktor | Jumlah Use Case yang Diakses | Contoh Use Case Eksklusif |
|---|---|---|
| Administrator | 17 dari 22 use case | Backup/Restore Database, Pengaturan Sistem, Activity Log |
| Guru | 11 dari 22 use case | Kelola Materi, Kelola Tugas & Penilaian, Kelola Kuis & Ujian |
| Siswa | 9 dari 22 use case | Mengerjakan Tugas, Mengerjakan Kuis/Ujian, Lihat Nilai & Presensi Pribadi |

### 4.2.3 Activity Diagram

Tujuh alur kerja yang memiliki percabangan keputusan bermakna dimodelkan dalam bentuk *Activity Diagram*, mewakili proses inti ketiga peran pengguna serta dua proses administratif yang berkorelasi langsung dengan temuan pengujian pada subbab 4.3.5 dan 4.4.1:

1. **Siswa Mengerjakan Ujian Online** — memodelkan alur mulai dari pengecekan rentang waktu ujian, pembuatan *record* percobaan (*exam attempt*), auto-save jawaban, hingga percabangan status akhir: `graded` (jika seluruh soal pilihan ganda, skor final langsung tersedia) atau `submitted` (jika terdapat soal esai, menunggu penilaian guru).
2. **Guru Membuat Tugas dan Menilai** — memodelkan alur mulai dari pembuatan tugas, notifikasi otomatis ke siswa terdaftar, pengumpulan oleh siswa, penilaian oleh guru, hingga perhitungan ulang rekap nilai mata pelajaran secara otomatis.
3. **Siswa Mengumpulkan Tugas** — memodelkan percabangan penting terkait tenggat waktu: pengumpulan ditolak jika melewati *deadline* dan guru tidak mengizinkan keterlambatan (`allow_late = false`); jika diizinkan, status pengumpulan ditandai `late`, sedangkan pengumpulan tepat waktu ditandai `submitted`.
4. **Guru Membuat Kuis/Ujian dengan Soal Pilihan Ganda & Esai** — memodelkan percabangan tipe soal saat penambahan butir soal: soal pilihan ganda memerlukan opsi jawaban dan penanda opsi benar, sedangkan soal esai hanya memerlukan pertanyaan dan skor maksimal.
5. **Guru Menginput Presensi** — memodelkan alur pemilihan jadwal dan tanggal sesi, penentuan status kehadiran per siswa (hadir/izin/sakit/alpha), hingga penyimpanan massal per sesi.
6. **Admin Menghapus Data Master dengan Proteksi Relasi** — memodelkan mekanisme yang secara nyata ditemukan dan diperbaiki selama pengembangan (subbab 4.3.5): pemeriksaan relasi aktif sebelum penghapusan, dan pembungkusan operasi dalam transaksi basis data untuk menjaga konsistensi data.
7. **Admin Backup & Restore Database** — memodelkan percabangan keberhasilan/kegagalan proses eksternal (`mysqldump`/`mysql` via Symfony Process), termasuk penanganan galat yang *graceful* tanpa merusak data yang sudah ada.

Diagram lengkap ketujuh alur kerja tersebut tersedia pada `docs/DIAGRAM.md` bagian 2.1–2.7.

### 4.2.4 Sequence Diagram

Sembilan interaksi antarkomponen dimodelkan dalam bentuk *Sequence Diagram*, mencakup alur permintaan-tanggapan (HTTP), pemrosesan asinkron (AJAX), orkestrasi antar-*service*, hingga interaksi dengan proses eksternal sistem operasi:

1. **Login dan Redirect Berdasarkan Peran** — alur autentikasi melalui Laravel Fortify hingga pengalihan ke dashboard sesuai peran oleh `DashboardController`.
2. **Siswa Mengumpulkan Tugas** — validasi berkas, penyimpanan ke *disk* `public`, dan pencatatan *submission*.
3. **Ekspor Laporan PDF/Excel** — interaksi `ReportController` dengan DomPDF dan Maatwebsite Excel.
4. **Siswa Menjawab Soal Kuis (AJAX Auto-Save & Auto-Scoring)** — permintaan asinkron per-soal dari *browser* ke `QuizController::answer()`, mencakup percabangan penilaian otomatis pilihan ganda vs. esai yang ditunda ke guru; interaksi ini menjadi dasar teknis akurasi yang dibuktikan pada TC-09 (subbab 4.4.1).
5. **RBAC Middleware — Penolakan Akses Lintas Peran** — bagaimana `RoleMiddleware` (Spatie) mencegat permintaan sebelum mencapai *Controller* ketika peran pengguna tidak sesuai, menghasilkan HTTP 403; melandasi kebutuhan NF-02 dan kasus uji TC-01.
6. **Guru Membuat Tugas → Notifikasi Massal ke Siswa Terdaftar** — pola *fan-out* satu aksi guru memicu `notify()` berulang ke seluruh siswa yang terdaftar (*enrolled*) pada *course* terkait.
7. **Guru Menilai Tugas → Rekap Nilai Otomatis + Notifikasi** — orkestrasi `SubmissionController` memanggil `GradeService::recalculateForCourse()` sekaligus mengirim notifikasi ke siswa dalam satu aksi.
8. **Admin Backup Database ke File SQL** — interaksi `BackupController` dengan proses eksternal (`Symfony\Process` menjalankan biner `mysqldump`), termasuk jalur kegagalan proses.
9. **Guru Menilai Esai Kuis Secara Manual** — skor esai yang di-*cap* ke skor maksimal soal, diikuti pemanggilan ulang `GradeService` untuk memperbarui rekap nilai.

Diagram lengkap kesembilan interaksi tersebut tersedia pada `docs/DIAGRAM.md` bagian 3.1–3.9.

### 4.2.5 Class Diagram

Struktur statis sistem — kelas, atribut, method bisnis, dan relasi antarkelas — diturunkan langsung dari 28 Model Eloquent pada `app/Models/`, sehingga class diagram menjadi jembatan antara rancangan basis data (ERD, subbab 4.2.6) dan implementasi kode aktual (subbab 4.3). Karena memuat 28 kelas, class diagram dipecah menjadi tiga diagram per modul agar tetap terbaca, mengikuti pengelompokan yang sama dengan ERD:

1. **Modul Data Master & Akademik** (10 kelas: `User`, `Teacher`, `Student`, `Department`, `AcademicYear`, `Subject`, `ClassRoom`, `Course`, `Enrollment`, `Schedule`) — memuat method bisnis seperti `User::isAdmin()`/`isTeacher()`/`isStudent()` untuk pengecekan peran, dan `Course::getNameAttribute()` untuk representasi nama gabungan mata pelajaran-kelas.
2. **Modul Pembelajaran & Penilaian** (13 kelas: `Material`, `Assignment`, `Submission`, `Quiz`, `QuizQuestion`, `QuizAttempt`, `QuizAnswer`, `Exam`, `ExamQuestion`, `ExamAttempt`, `ExamAnswer`, `Grade`, `Attendance`) — memuat method bisnis kunci yang telah dibahas pada subbab 4.3.4, seperti `Assignment::isPastDeadline()`, `Quiz::isOpen()`, dan `Grade::letterFor()`.
3. **Modul Komunikasi & Sistem** (5 kelas: `Announcement`, `Discussion`, `DiscussionComment`, `Setting`, `ActivityLog`) — mencakup relasi rekursif `DiscussionComment` terhadap dirinya sendiri (balasan komentar berjenjang) dan method statis `Setting::get()`/`set()` yang memanfaatkan *cache* aplikasi.

Diagram lengkap ketiga modul tersebut tersedia pada `docs/DIAGRAM.md` bagian 4.1–4.3.

### 4.2.6 Entity Relationship Diagram (ERD)

Basis data dirancang dengan **30 entitas domain aplikasi** (di luar tabel infrastruktur bawaan Laravel dan RBAC), meliputi entitas data master (`users`, `teachers`, `students`, `departments`, `classes`, `academic_years`, `subjects`), entitas transaksi pembelajaran (`courses`, `enrollments`, `materials`, `assignments`, `submissions`, `quizzes`, `quiz_questions`, `quiz_attempts`, `quiz_answers`, `exams`, `exam_questions`, `exam_attempts`, `exam_answers`, `grades`, `attendances`), serta entitas pendukung (`schedules`, `announcements`, `discussions`, `discussion_comments`, `notifications`, `settings`, `activity_logs`). Diagram ERD lengkap dalam notasi Mermaid tersedia pada `docs/ERD.md`.

Rancangan integritas referensial menerapkan tiga jenis aksi hapus sesuai makna bisnis masing-masing relasi:

- **`RESTRICT`** — mencegah penghapusan data induk yang masih direferensikan, contoh: `teachers` tidak dapat dihapus selama masih memiliki `courses` aktif, dan `departments` tidak dapat dihapus selama masih memiliki `classes`.
- **`CASCADE`** — data anak ikut terhapus karena tidak bermakna tanpa induknya, contoh: `quiz_questions` ikut terhapus jika `quiz` induknya dihapus.
- **`SET NULL`** — relasi opsional yang menjadi kosong, contoh: `homeroom_teacher_id` pada `classes` menjadi `NULL` jika guru wali kelasnya dihapus.

Constraint `UNIQUE` diterapkan pada kombinasi kolom yang secara bisnis wajib unik, di antaranya `courses` (unik per kombinasi `subject_id`, `class_id`, `academic_year_id` — satu mata pelajaran hanya diampu satu guru per kelas per tahun ajaran) dan `enrollments` (unik per `student_id` + `course_id`).

## 4.3 Hasil Tahap Development

### 4.3.1 Lingkungan Pengembangan

| Komponen | Teknologi/Versi yang Digunakan |
|---|---|
| Bahasa pemrograman | PHP 8.2.12 |
| Framework | Laravel 12 (terverifikasi berjalan pada versi 12.64.0) |
| Basis data | MySQL/MariaDB (XAMPP) |
| Manajer dependensi | Composer 2.10.2 |
| Autentikasi | Laravel Fortify ^1.37 |
| Otorisasi (RBAC) | Spatie Laravel-Permission ^6.15 |
| Ekspor PDF | barryvdh/laravel-dompdf ^3.1 |
| Ekspor Excel | maatwebsite/excel ^3.1 |
| Frontend | Bootstrap 5, Font Awesome 6, Chart.js (di-*vendor* lokal ke `public/vendor/`, tanpa CDN/Node.js) |
| Server lokal | XAMPP (Apache + MySQL), Windows |

### 4.3.2 Statistik Implementasi

| Komponen | Jumlah |
|---|---|
| Controller | 49 (24 Admin + 14 Guru + 11 Siswa) |
| Model Eloquent | 28 |
| Service (logika bisnis khusus) | 2 (`GradeService`, `ActivityLogger`) |
| Berkas migrasi basis data | 32 |
| Tabel fisik di basis data | 42 (30 domain + 5 RBAC Spatie + 7 infrastruktur Laravel: `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `sessions`, `password_reset_tokens`, `migrations`) |

### 4.3.3 Implementasi Modul per Peran

**Modul Administrator** (`app/Http/Controllers/Admin/`) — 24 controller mencakup manajemen data master (User, Teacher, Student, Department, Subject, AcademicYear, ClassRoom), penugasan mengajar (Course), seluruh modul pembelajaran lintas kelas (Material, Assignment, Quiz beserta QuizQuestion, Exam beserta ExamQuestion, Grade, Attendance, Schedule), komunikasi (Announcement, Discussion), serta modul administratif (ActivityLog, Setting, Backup, Report, Dashboard).

**Modul Guru** (`app/Http/Controllers/Guru/`) — 14 controller yang seluruhnya menerapkan pembatasan kepemilikan data (*ownership check*) — guru hanya dapat mengelola mata pelajaran, materi, tugas, kuis, ujian, dan presensi milik kelas yang diampunya sendiri. Sebagai contoh, `MaterialController::authorizeCourse()` (`app/Http/Controllers/Guru/MaterialController.php:147-153`) memverifikasi bahwa `course_id` yang diajukan benar-benar dimiliki oleh guru yang sedang login sebelum mengizinkan operasi simpan/ubah.

**Modul Siswa** (`app/Http/Controllers/Siswa/`) — 11 controller yang membatasi akses hanya pada data milik siswa yang bersangkutan, diverifikasi melalui pengecekan pendaftaran (*enrollment*) pada setiap course sebelum materi/tugas/kuis/ujian dapat diakses — contoh pada `QuizController::authorizeAccess()` (`app/Http/Controllers/Siswa/QuizController.php:154-161`).

### 4.3.4 Implementasi Logika Bisnis Kunci

**a. Penilaian Otomatis Kuis (Auto-Scoring)**

Untuk soal bertipe pilihan ganda, sistem menghitung kebenaran jawaban dan skor secara otomatis setiap kali siswa menyimpan jawaban (`app/Http/Controllers/Siswa/QuizController.php:88-113`), dengan membandingkan `selected_option` terhadap `correct_option` pada tabel `quiz_questions`. Untuk soal esai, skor tetap `NULL` hingga dinilai manual oleh guru. Saat kuis diselesaikan (`finalize()`, baris 138-152), status ditentukan otomatis: `graded` jika seluruh soal berjenis pilihan ganda (skor final langsung tersedia dan rekap nilai mata pelajaran dihitung ulang via `GradeService`), atau `submitted` jika terdapat soal esai (menunggu penilaian guru).

**b. Timer dan Auto-Submit Ujian/Kuis**

Batas waktu pengerjaan dihitung sebagai `min(waktu_mulai + durasi_menit, waktu_selesai_kuis)` (`app/Http/Controllers/Siswa/QuizController.php:77`), sehingga siswa yang baru memulai kuis mendekati waktu tutup tetap dibatasi oleh waktu tutup kuis, bukan hanya durasi pengerjaannya. Ketika waktu habis dan siswa membuka kembali halaman pengerjaan, sistem otomatis memanggil `finalize()` tanpa memerlukan aksi manual siswa.

**c. Rekap Nilai Otomatis**

`GradeService::recalculateForCourse()` (`app/Services/GradeService.php:13-51`) menghitung nilai akhir setiap siswa dalam suatu mata pelajaran sebagai rata-rata dari tiga komponen (rata-rata skor tugas, rata-rata skor kuis, rata-rata skor ujian) yang tersedia (komponen yang belum ada nilainya diabaikan dari perhitungan, bukan dianggap nol), kemudian memetakan nilai akhir ke huruf mutu (A ≥ 90, B ≥ 80, C ≥ 70, D ≥ 60, selain itu E) melalui `Grade::letterFor()`.

**d. Validasi Unggah Berkas**

Unggahan materi pembelajaran dibatasi pada tipe berkas `pdf, doc, docx, ppt, pptx, mp4, jpg, jpeg, png` dengan ukuran maksimum 20 MB (`app/Http/Controllers/Guru/MaterialController.php:17,44`), serta menghapus berkas lama dari *storage* saat materi diperbarui dengan berkas baru (baris 106-114) untuk mencegah berkas yatim menumpuk di server.

**e. Keamanan Aplikasi**

Token CSRF diterapkan pada seluruh form (`@csrf`, divalidasi middleware bawaan Laravel), *output escaping* otomatis Blade (`{{ }}`) mencegah XSS, seluruh akses data menggunakan Eloquent ORM/Query Builder dengan *parameter binding* untuk mencegah SQL Injection, kata sandi di-*hash* menggunakan bcrypt, sesi disimpan di basis data, dan percobaan login dibatasi 5 kali per menit per kombinasi email + alamat IP melalui Laravel Fortify.

### 4.3.5 Temuan dan Perbaikan Selama Pengembangan

Selama proses pengembangan dan pengujian berjalan (22 Juli 2026), ditemukan satu cacat fungsional (*bug*) pada modul manajemen Guru: penghapusan akun guru yang masih mengampu mata pelajaran aktif menyebabkan **HTTP 500 (Unhandled QueryException)** karena constraint `RESTRICT` pada `courses.teacher_id` (lihat subbab 4.2.6) belum divalidasi di lapisan Controller sebelum operasi hapus dieksekusi. Perbaikan yang diterapkan:

1. Menambahkan pemeriksaan `$teacher->courses()->exists()` sebelum penghapusan, mengembalikan pesan galat yang informatif ("Guru tidak dapat dihapus karena masih mengampu mata pelajaran...") alih-alih membiarkan *exception* mentah tampil ke pengguna (`app/Http/Controllers/Admin/TeacherController.php:135-137`).
2. Membungkus operasi penghapusan dengan `DB::transaction()` (`app/Http/Controllers/Admin/TeacherController.php:139-142`) untuk mencegah kondisi tidak konsisten — pada percobaan sebelum perbaikan, sebagian data (baris `model_has_roles` dan `model_has_permissions`) sempat terhapus meskipun baris `users` gagal dihapus karena tidak ada transaksi yang membungkusnya.

Temuan ini menjadi salah satu kasus uji black-box (TC-06/TC-06b, subbab 4.4.1) dan menunjukkan bahwa siklus pengembangan-pengujian-perbaikan pada penelitian ini berjalan secara nyata, bukan sekadar simulasi di atas kertas.

## 4.4 Hasil Tahap Evaluation

### 4.4.1 Hasil Pengujian Black-Box

Pengujian dilakukan dengan dua metode: **(a) otomatis**, mensimulasikan permintaan HTTP nyata terhadap aplikasi yang sedang berjalan di `http://localhost/LMS9/public` menggunakan sesi dan token CSRF yang sah, dan **(b) verifikasi data/kode**, memeriksa konsistensi data hasil proses bisnis terhadap logika program yang menghasilkannya. Seluruh pengujian pada tabel berikut dieksekusi pada **22 Juli 2026** terhadap sistem yang benar-benar berjalan (bukan simulasi/perkiraan).

| ID | Kebutuhan Terkait | Skenario Pengujian | Data/Aksi Uji | Hasil Diharapkan | Hasil Aktual | Kesimpulan | Metode |
|---|---|---|---|---|---|---|---|
| TC-01 | NF-02, F-A01 | Akses halaman admin tanpa login | `GET /admin/teachers` tanpa sesi aktif | Dialihkan ke halaman login | Dialihkan ke halaman login (konten memuat judul "Login - LMS") | **Valid** | Otomatis (HTTP nyata) |
| TC-02 | F-A01 | Login sukses sebagai Administrator | `admin@smkn9garut.sch.id` / `password` | Dialihkan ke `/admin/dashboard` | Login → `/dashboard` → `/admin/dashboard` | **Valid** | Otomatis (HTTP nyata) |
| TC-03 | F-G01 | Login sukses sebagai Guru | `guru1@smkn9garut.sch.id` / `password` | Dialihkan ke `/guru/dashboard` | Login → `/dashboard` → `/guru/dashboard` | **Valid** | Otomatis (HTTP nyata) |
| TC-04 | F-S01 | Login sukses sebagai Siswa | `siswa1@smkn9garut.sch.id` / `password` | Dialihkan ke `/siswa/dashboard` | Login → `/dashboard` → `/siswa/dashboard` | **Valid** | Otomatis (HTTP nyata) |
| TC-05 | NF-02 | Login dengan kata sandi salah | `admin@smkn9garut.sch.id` / kata sandi acak | Login ditolak, kembali ke `/login` | Redirect ke `/login`, tidak masuk ke area dashboard manapun | **Valid** | Otomatis (HTTP nyata) |
| TC-06 | F-A03, NF-04 | Hapus guru yang masih mengampu mata pelajaran aktif | `DELETE /admin/teachers/3` (guru dengan 12 course aktif) | Ditolak dengan pesan galat, bukan crash (HTTP 500) | HTTP 302 (redirect aman) + pesan flash "Guru tidak dapat dihapus karena masih mengampu mata pelajaran..." tampil | **Valid** *(setelah perbaikan pada 4.3.5; sebelum perbaikan: **Tidak Valid**, HTTP 500)* | Otomatis (HTTP nyata) |
| TC-07 | F-A01 | Logout Administrator | `POST /logout` | Dialihkan keluar dari area admin | HTTP 302 redirect | **Valid** | Otomatis (HTTP nyata) |
| TC-08 | F-A04 | Konstrain unik penugasan mengajar (1 mapel = 1 guru per kelas per tahun ajaran) | `INSERT` kombinasi `subject_id=2, class_id=1, academic_year_id=2` yang sudah ada | Ditolak oleh basis data (duplikat) | `ERROR 1062: Duplicate entry '2-1-2' for key 'courses_unique_assignment'` | **Valid** | Otomatis (query SQL nyata) |
| TC-09 | F-S04, F-S05 | Konsistensi penilaian otomatis kuis pilihan ganda | Verifikasi seluruh 600 jawaban pilihan ganda yang tersimpan (`is_correct` dan `score` vs `correct_option`) | 0 baris tidak konsisten | 600 dari 600 jawaban konsisten (0 anomali `is_correct`, 0 anomali skor) | **Valid** | Verifikasi data (query agregat SQL) |
| TC-10 | F-G08, F-S06 | Konsistensi rekap huruf mutu terhadap nilai akhir | Verifikasi seluruh 590 baris `grades` terhadap ambang `Grade::letterFor()` | 0 baris tidak konsisten | 590 dari 590 baris konsisten | **Valid** | Verifikasi data (query agregat SQL) |
| TC-11 | F-G03, NF-02 | Validasi tipe & ukuran berkas materi | Tinjau aturan validasi `mimes:pdf,doc,docx,ppt,pptx,mp4,jpg,jpeg,png\|max:20480` pada `MaterialController::store()` | Berkas di luar tipe/ukuran yang diizinkan ditolak Laravel Validator sebelum tersimpan | Aturan validasi ditemukan diterapkan pada `store()` dan `update()` (baris 44 dan 90) | **Valid** | Verifikasi kode |
| TC-12 | F-S04 | Batas waktu pengerjaan kuis mengikuti waktu tutup kuis, bukan hanya durasi | Tinjau perhitungan `$endsAt = min(started_at + duration, quiz.end_time)` | Siswa tidak bisa mengerjakan melewati waktu tutup kuis meski baru memulai mendekati waktu tutup | Logika `min()` ditemukan diterapkan pada `QuizController::attempt()` baris 77 | **Valid** | Verifikasi kode |
| TC-13 | NF-02 | Kepemilikan data guru (guru tidak bisa mengelola materi guru lain) | Tinjau `MaterialController::authorizeCourse()` dan `edit()`/`update()`/`destroy()` | Akses ditolak (403) jika `course`/`material` bukan milik guru yang login | Pengecekan `abort_unless(... teacher_id === $request->user()->teacher->id ...)` ditemukan pada baris 76, 83, 124, 147-153 | **Valid** | Verifikasi kode |

**Ringkasan hasil pengujian black-box:** dari **13 kasus uji**, seluruhnya (**13/13 atau 100%**) dinyatakan **Valid** pada kondisi akhir sistem (setelah perbaikan bug TC-06). Rincian lengkap prosedur pengujian otomatis tersedia pada `LAMPIRAN.md`.

> ⚠️ **TODO (Anda lengkapi):** Tabel di atas adalah subset representatif yang membuktikan kebutuhan fungsional inti (bukan seluruh 27 kebutuhan F-A01–F-S08). Untuk skripsi yang lebih kuat, lengkapi pengujian manual melalui antarmuka browser untuk kebutuhan yang belum tercakup di atas (mis. F-A07 ekspor PDF/Excel, F-A10 backup/restore, F-G06 penilaian esai manual, F-A06/F-G09/F-S07 pengumuman & forum diskusi), lalu tambahkan baris TC-14 dst. mengikuti format yang sama. Sertakan tangkapan layar (*screenshot*) sebagai bukti pada `LAMPIRAN.md` untuk kasus uji yang diuji manual.

### 4.4.2 Hasil Evaluasi Pengguna (User Acceptance Testing)

> ⚠️ **TODO (Anda lengkapi — WAJIB data primer asli):** Bagian ini **tidak boleh diisi dengan data karangan**. Instrumen kuesioner (kisi-kisi dan daftar pertanyaan per peran) sudah disiapkan lengkap pada `LAMPIRAN.md`. Langkah yang perlu Anda lakukan:
> 1. Cetak/bagikan kuesioner pada `LAMPIRAN.md` kepada responden nyata (lihat jumlah responden pada subbab 3.3).
> 2. Rekap jawaban ke dalam tabel skor per butir pertanyaan.
> 3. Hitung persentase kelayakan per aspek menggunakan rumus pada subbab 3.7.2.
> 4. Tempelkan tabel hasil rekap dan interpretasinya di sini, contoh kerangka tabel:
>
> | Aspek | Jumlah Butir | Skor Diperoleh | Skor Maksimal Ideal | Persentase | Kategori |
> |---|---|---|---|---|---|
> | Usability (Admin) | ... | ... | ... | ...% | ... |
> | Usability (Guru) | ... | ... | ... | ...% | ... |
> | Usability (Siswa) | ... | ... | ... | ...% | ... |
> | Security & Reliability | ... | ... | ... | ...% | ... |
> | Kesesuaian Fungsi | ... | ... | ... | ...% | ... |

## 4.5 Pembahasan

Hasil pengujian black-box menunjukkan bahwa **seluruh 13 kasus uji yang dilaksanakan dinyatakan valid (100%)** pada kondisi akhir sistem, mencakup pembuktian langsung terhadap mekanisme autentikasi dan RBAC (TC-01–TC-05, TC-07), integritas data pada level basis data (TC-08), akurasi logika penilaian otomatis pada skala data nyata 600 jawaban kuis dan 590 baris nilai (TC-09–TC-10), serta kepatuhan terhadap aturan validasi dan otorisasi kepemilikan data yang tertulis eksplisit dalam kode program (TC-11–TC-13).

Temuan yang paling relevan secara metodologis adalah kasus TC-06, yang pada pengujian pertama (sebelum perbaikan) dinyatakan **Tidak Valid** karena sistem gagal menangani kondisi *foreign key constraint* secara *graceful* dan menampilkan *error* teknis (HTTP 500) kepada pengguna akhir. Temuan ini langsung ditindaklanjuti dengan perbaikan kode pada lapisan Controller (subbab 4.3.5), dan pengujian ulang (*regression testing*) membuktikan perbaikan berhasil. Kejadian ini menggambarkan bahwa tahap **Evaluation** dalam kerangka DDR bukan sekadar formalitas administratif, melainkan benar-benar berfungsi sebagai mekanisme umpan balik (*feedback loop*) yang memperbaiki kualitas produk sebelum dinyatakan selesai — sejalan dengan prinsip DDR bahwa evaluasi menghasilkan bukti empiris kelayakan produk (Richey & Klein, 2007), bukan asumsi semata.

Adapun hasil evaluasi penerimaan pengguna (UAT) belum dapat disimpulkan pada naskah ini karena memerlukan data primer dari responden sesungguhnya (subbab 4.4.2). Pembahasan akhir mengenai tingkat penerimaan pengguna wajib dilengkapi setelah data kuesioner terkumpul, sebelum bab ini dinyatakan final.
