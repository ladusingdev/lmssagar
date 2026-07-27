# Diagram Perancangan Sistem — LMS SMKN 9 Garut

Diagram berikut menggunakan notasi [Mermaid](https://mermaid.js.org/), yang otomatis dirender oleh GitHub, GitLab, dan sebagian besar editor Markdown modern (VS Code dengan ekstensi Mermaid, dsb).

## 1. Use Case Diagram

```mermaid
graph TB
    Admin([Administrator])
    Guru([Guru])
    Siswa([Siswa])

    subgraph Sistem LMS SMKN 9 Garut
        UC1[Login / Logout]
        UC2[Kelola Data Master<br/>User, Guru, Siswa, Jurusan, Kelas, Mapel]
        UC3[Kelola Penugasan Mengajar]
        UC4[Kelola Materi Pembelajaran]
        UC5[Kelola Tugas & Penilaian]
        UC6[Kelola Kuis & Ujian Online]
        UC7[Kelola Presensi]
        UC8[Kelola Nilai]
        UC9[Kelola Jadwal]
        UC10[Kelola Pengumuman]
        UC11[Forum Diskusi]
        UC12[Laporan - Cetak/PDF/Excel]
        UC13[Activity Log]
        UC14[Pengaturan Sistem]
        UC15[Backup / Restore Database]
        UC16[Lihat Dashboard]
        UC17[Upload / Download Materi]
        UC18[Mengerjakan Tugas]
        UC19[Mengerjakan Kuis / Ujian]
        UC20[Lihat Nilai & Presensi Pribadi]
        UC21[Kelola Profil & Password]
        UC22[Notifikasi]
    end

    Admin --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC5
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
    Admin --> UC9
    Admin --> UC10
    Admin --> UC11
    Admin --> UC12
    Admin --> UC13
    Admin --> UC14
    Admin --> UC15
    Admin --> UC16
    Admin --> UC21

    Guru --> UC1
    Guru --> UC4
    Guru --> UC5
    Guru --> UC6
    Guru --> UC7
    Guru --> UC8
    Guru --> UC9
    Guru --> UC10
    Guru --> UC11
    Guru --> UC16
    Guru --> UC17
    Guru --> UC21

    Siswa --> UC1
    Siswa --> UC11
    Siswa --> UC16
    Siswa --> UC17
    Siswa --> UC18
    Siswa --> UC19
    Siswa --> UC20
    Siswa --> UC21
    Siswa --> UC22
```

## 2. Activity Diagram

### 2.1 Siswa Mengerjakan Ujian Online

```mermaid
flowchart TD
    Start([Mulai]) --> A[Siswa membuka daftar Ujian Online]
    A --> B{Ujian tersedia<br/>dalam rentang waktu?}
    B -- Tidak --> Z1[Tampilkan status<br/>Belum Dibuka / Ditutup]
    Z1 --> End1([Selesai])
    B -- Ya --> C[Siswa klik Mulai Ujian]
    C --> D[Sistem membuat record<br/>Exam Attempt in_progress]
    D --> E[Sistem menampilkan soal<br/>+ hitung mundur waktu]
    E --> F[Siswa menjawab tiap soal]
    F --> G[Sistem auto-save jawaban<br/>via AJAX]
    G --> H{Semua soal terjawab<br/>atau waktu habis?}
    H -- Belum --> F
    H -- Ya, disubmit manual --> I[Siswa klik Kumpulkan Ujian]
    H -- Ya, waktu habis --> J[Sistem auto-submit]
    I --> K[Sistem hitung skor otomatis<br/>untuk soal pilihan ganda]
    J --> K
    K --> L{Ada soal essay?}
    L -- Ya --> M[Status: submitted<br/>Menunggu penilaian guru]
    L -- Tidak --> N[Status: graded<br/>Nilai final langsung tersedia]
    M --> O[Siswa melihat hasil sementara]
    N --> P[Siswa melihat hasil akhir]
    O --> End2([Selesai])
    P --> End2
```

### 2.2 Guru Membuat Tugas dan Menilai

```mermaid
flowchart TD
    Start([Mulai]) --> A[Guru login]
    A --> B[Pilih Mata Pelajaran Saya]
    B --> C[Buat Tugas Baru<br/>judul, deskripsi, deadline, lampiran]
    C --> D[Sistem publikasikan tugas<br/>+ kirim notifikasi ke siswa terdaftar]
    D --> E[Siswa mengumpulkan tugas]
    E --> F[Guru membuka daftar pengumpulan]
    F --> G[Guru memberi nilai + komentar]
    G --> H[Sistem simpan nilai<br/>+ hitung ulang rekap nilai mata pelajaran]
    H --> I[Sistem kirim notifikasi nilai ke siswa]
    I --> End([Selesai])
```

### 2.3 Siswa Mengumpulkan Tugas

```mermaid
flowchart TD
    Start([Mulai]) --> A[Siswa membuka daftar Tugas]
    A --> B[Siswa pilih salah satu Tugas]
    B --> C{Tugas sudah melewati<br/>deadline?}
    C -- Tidak --> F[Siswa unggah file jawaban<br/>+ catatan opsional]
    C -- Ya --> D{Guru mengizinkan<br/>pengumpulan terlambat?<br/>allow_late}
    D -- Tidak --> E[Sistem tolak pengumpulan<br/>Tampilkan pesan:<br/>Batas waktu telah berakhir]
    E --> End1([Selesai])
    D -- Ya --> F
    F --> G[Sistem validasi berkas<br/>mimes & ukuran maks 20MB]
    G --> H{Lolos validasi?}
    H -- Tidak --> I[Tampilkan pesan error validasi]
    I --> F
    H -- Ya --> J{Waktu submit<br/>melewati deadline?}
    J -- Ya --> K[Simpan submission<br/>status: late]
    J -- Tidak --> L[Simpan submission<br/>status: submitted]
    K --> M[Tampilkan konfirmasi<br/>Tugas berhasil dikumpulkan]
    L --> M
    M --> End2([Selesai])
```

### 2.4 Guru Membuat Kuis/Ujian dengan Soal Pilihan Ganda & Esai

```mermaid
flowchart TD
    Start([Mulai]) --> A[Guru login & pilih Mata Pelajaran Saya]
    A --> B[Buat Kuis Baru<br/>judul, durasi, waktu mulai/selesai,<br/>acak soal, tampilkan hasil langsung]
    B --> C[Sistem simpan kuis<br/>+ arahkan ke halaman Tambah Soal]
    C --> D[Guru pilih tipe soal]
    D --> E{Tipe soal?}
    E -- Pilihan Ganda --> F[Isi pertanyaan + opsi A-E<br/>+ tentukan opsi benar + skor]
    E -- Esai --> G[Isi pertanyaan + skor maksimal<br/>tanpa opsi jawaban]
    F --> H[Sistem simpan soal]
    G --> H
    H --> I{Guru tambah<br/>soal lagi?}
    I -- Ya --> D
    I -- Tidak --> J[Guru publikasikan kuis<br/>is_published = true]
    J --> End([Selesai - Kuis siap dikerjakan siswa])
```

### 2.5 Guru Menginput Presensi

```mermaid
flowchart TD
    Start([Mulai]) --> A[Guru buka menu Presensi]
    A --> B[Pilih jadwal mengajar<br/>+ tanggal sesi]
    B --> C{Presensi tanggal ini<br/>sudah pernah diisi?}
    C -- Ya --> D[Sistem tampilkan status<br/>presensi sebelumnya sebagai draf]
    C -- Tidak --> E[Sistem tampilkan daftar siswa<br/>di kelas terjadwal]
    D --> F
    E --> F[Guru set status per siswa:<br/>hadir/izin/sakit/alpha + catatan opsional]
    F --> G[Guru simpan presensi]
    G --> H[Sistem simpan/perbarui presensi<br/>per siswa - updateOrCreate]
    H --> End([Selesai])
```

### 2.6 Admin Menghapus Data Master dengan Proteksi Relasi

> Diagram ini memodelkan mekanisme yang secara nyata diuji dan diperbaiki selama pengembangan (lihat Bab IV subbab 4.3.5 &amp; kasus uji TC-06) — contoh kasus: penghapusan akun Guru yang masih mengampu mata pelajaran aktif.

```mermaid
flowchart TD
    Start([Mulai]) --> A[Admin membuka daftar Guru]
    A --> B[Admin klik Hapus pada salah satu guru]
    B --> C{Guru masih memiliki<br/>mata pelajaran/course aktif?}
    C -- Ya --> D[Sistem tolak penghapusan<br/>Tampilkan pesan error:<br/>Guru tidak dapat dihapus karena<br/>masih mengampu mata pelajaran]
    D --> End1([Selesai - Data tidak berubah])
    C -- Tidak --> E[Sistem jalankan penghapusan<br/>dalam DB Transaction]
    E --> F[Hapus role & permission user]
    F --> G[Hapus akun user & profil guru]
    G --> H{Transaksi berhasil?}
    H -- Tidak --> I[Rollback seluruh perubahan<br/>Tampilkan pesan error]
    I --> End2([Selesai - Data tetap konsisten])
    H -- Ya --> J[Commit transaksi<br/>Catat Activity Log]
    J --> K[Tampilkan konfirmasi<br/>Data guru berhasil dihapus]
    K --> End3([Selesai])
```

### 2.7 Admin Backup & Restore Database

```mermaid
flowchart TD
    Start([Mulai]) --> A{Admin pilih aksi}
    A -- Backup --> B[Sistem jalankan proses<br/>mysqldump via Symfony Process]
    B --> C{Proses berhasil?}
    C -- Tidak --> D[Tampilkan pesan error:<br/>Gagal membuat backup]
    D --> End1([Selesai])
    C -- Ya --> E[Simpan file .sql<br/>ke storage/app/backups]
    E --> F[Catat Activity Log<br/>Tampilkan daftar file backup]
    F --> End2([Selesai])
    A -- Restore --> G[Admin unggah file .sql<br/>maks 50MB]
    G --> H{Validasi ekstensi<br/>& ukuran lolos?}
    H -- Tidak --> I[Tampilkan pesan error validasi]
    I --> End3([Selesai])
    H -- Ya --> J[Sistem jalankan proses<br/>mysql import via Symfony Process]
    J --> K{Proses berhasil?}
    K -- Tidak --> L[Tampilkan pesan error:<br/>Gagal melakukan restore]
    L --> End4([Selesai - Database tidak berubah])
    K -- Ya --> M[Catat Activity Log<br/>Tampilkan konfirmasi:<br/>Database berhasil di-restore]
    M --> End5([Selesai])
```

## 3. Sequence Diagram

### 3.1 Login dan Redirect Berdasarkan Peran

```mermaid
sequenceDiagram
    actor U as Pengguna
    participant B as Browser
    participant F as Laravel Fortify
    participant DB as Database
    participant D as DashboardController

    U->>B: Input email & password
    B->>F: POST /login
    F->>DB: Cek kredensial & status is_active
    DB-->>F: User ditemukan & aktif
    F->>F: Verifikasi password (Hash::check)
    F-->>B: Set session + redirect ke /dashboard
    B->>D: GET /dashboard
    D->>DB: Cek role user (admin/guru/siswa)
    DB-->>D: Role: misal "guru"
    D-->>B: Redirect ke /guru/dashboard
    B->>B: Tampilkan Dashboard Guru
```

### 3.2 Siswa Mengumpulkan Tugas

```mermaid
sequenceDiagram
    actor S as Siswa
    participant B as Browser
    participant C as AssignmentController (Siswa)
    participant Store as Storage (public disk)
    participant DB as Database
    participant N as Notification

    S->>B: Buka halaman detail tugas
    B->>C: GET /siswa/assignments/{id}
    C->>DB: Ambil data tugas + submission (jika ada)
    DB-->>C: Data tugas
    C-->>B: Tampilkan form upload jawaban
    S->>B: Upload file jawaban + submit
    B->>C: POST /siswa/assignments/{id}/submit
    C->>C: Validasi file (mimes, ukuran maks)
    C->>Store: Simpan file ke storage/app/public/submissions
    C->>DB: Simpan/Update record Submission
    DB-->>C: OK
    C-->>B: Redirect + flash "Tugas berhasil dikumpulkan"
    Note over C,N: Saat guru menilai nanti,<br/>sistem mengirim notifikasi ke siswa
```

### 3.3 Export Laporan PDF/Excel

```mermaid
sequenceDiagram
    actor A as Administrator
    participant B as Browser
    participant R as ReportController
    participant DB as Database
    participant PDF as DomPDF
    participant XLS as Maatwebsite Excel

    A->>B: Klik "Export PDF" pada Laporan Data Siswa
    B->>R: GET /admin/reports/students/pdf
    R->>DB: Ambil seluruh data siswa (with relasi kelas & jurusan)
    DB-->>R: Collection Student
    R->>PDF: loadView(pdf.students, data)->stream()
    PDF-->>R: Dokumen PDF (binary)
    R-->>B: Response application/pdf
    B-->>A: Tampilkan / unduh file PDF

    A->>B: Klik "Export Excel"
    B->>R: GET /admin/reports/students/excel
    R->>XLS: Excel::download(new StudentsExport)
    XLS->>DB: Query data siswa
    DB-->>XLS: Collection Student
    XLS-->>R: File .xlsx
    R-->>B: Response spreadsheet
    B-->>A: Unduh file Excel
```

### 3.4 Siswa Menjawab Soal Kuis (AJAX Auto-Save & Auto-Scoring)

```mermaid
sequenceDiagram
    actor S as Siswa
    participant B as Browser (JS)
    participant C as QuizController (Siswa)
    participant DB as Database

    S->>B: Pilih jawaban / ketik jawaban esai
    B->>B: Trigger AJAX otomatis (on-change)
    B->>C: POST /siswa/quizzes/{quiz}/answer (AJAX)
    C->>C: Validasi question_id & selected_option
    C->>DB: Ambil correct_option dari quiz_questions
    DB-->>C: correct_option, skor soal
    alt Tipe soal: Pilihan Ganda
        C->>C: is_correct = (selected_option === correct_option)
        C->>C: score = is_correct ? skor_soal : 0
    else Tipe soal: Esai
        C->>C: is_correct = null, score = null (menunggu guru)
    end
    C->>DB: updateOrCreate QuizAnswer
    DB-->>C: OK
    C-->>B: JSON {"saved": true}
    B-->>S: Indikator "Tersimpan" tanpa reload halaman
```

### 3.5 RBAC Middleware — Penolakan Akses Lintas Peran

```mermaid
sequenceDiagram
    actor U as Pengguna (role: siswa)
    participant B as Browser
    participant MW_A as Middleware auth
    participant MW_R as Middleware role (Spatie RoleMiddleware)
    participant C as Admin\TeacherController
    participant DB as Database

    U->>B: Akses URL /admin/teachers
    B->>MW_A: GET /admin/teachers
    MW_A->>DB: Cek sesi pengguna valid?
    DB-->>MW_A: Sesi valid, user login sebagai "siswa"
    MW_A->>MW_R: Lanjutkan ke pengecekan role
    MW_R->>DB: Cek role user memiliki "admin"?
    DB-->>MW_R: Role user = "siswa" (tidak cocok)
    MW_R-->>B: HTTP 403 Forbidden
    Note over C: Controller Admin TeacherController<br/>tidak pernah dieksekusi
    B-->>U: Tampilkan halaman "403 - Akses Ditolak"
```

### 3.6 Guru Membuat Tugas → Notifikasi Massal ke Siswa Terdaftar

```mermaid
sequenceDiagram
    actor G as Guru
    participant B as Browser
    participant C as AssignmentController (Guru)
    participant DB as Database
    participant N as Notification (GeneralNotification)

    G->>B: Isi form tugas + submit
    B->>C: POST /guru/assignments
    C->>C: Validasi input (title, deadline, max_score, dst.)
    C->>DB: Simpan Assignment baru
    DB-->>C: Assignment tersimpan
    C->>DB: Ambil seluruh Student yang enrolled di Course ini
    DB-->>C: Daftar Student (n siswa)
    loop Untuk setiap siswa terdaftar
        C->>N: notify(GeneralNotification "Tugas Baru")
        N->>DB: Simpan notifikasi ke tabel notifications
    end
    C-->>B: Redirect + flash "Tugas berhasil ditambahkan"
    Note over N,DB: Setiap siswa melihat notifikasi<br/>saat membuka dashboard/lonceng notifikasi
```

### 3.7 Guru Menilai Tugas → Rekap Nilai Otomatis + Notifikasi

```mermaid
sequenceDiagram
    actor G as Guru
    participant B as Browser
    participant C as SubmissionController (Guru)
    participant DB as Database
    participant GS as GradeService
    participant N as Notification

    G->>B: Input skor + feedback, submit
    B->>C: PUT /guru/submissions/{submission}
    C->>C: Validasi skor (0 - max_score tugas)
    C->>DB: Update Submission (score, feedback, status=graded)
    DB-->>C: OK
    C->>GS: recalculateForCourse(course)
    GS->>DB: Ambil rata-rata skor tugas/kuis/ujian per siswa
    DB-->>GS: Data skor komponen
    GS->>DB: updateOrCreate Grade (final_score, letter_grade)
    DB-->>GS: OK
    C->>N: notify(GeneralNotification "Tugas Dinilai")
    N->>DB: Simpan notifikasi untuk siswa
    C-->>B: Redirect + flash "Nilai berhasil disimpan"
```

### 3.8 Admin Backup Database ke File SQL

```mermaid
sequenceDiagram
    actor A as Administrator
    participant B as Browser
    participant C as BackupController
    participant P as Symfony Process
    participant OS as Binary mysqldump.exe
    participant FS as Storage (local disk)
    participant DB as Database

    A->>B: Klik "Buat Backup"
    B->>C: POST /admin/backup
    C->>P: new Process([mysqldump, --host, --user, ..., database])
    P->>OS: Eksekusi binary mysqldump.exe
    OS->>DB: Dump seluruh struktur & data (single-transaction)
    DB-->>OS: Data basis data
    OS-->>P: Output dump SQL (stdout)
    alt Proses gagal (mis. path binary salah)
        P-->>C: ProcessFailedException
        C-->>B: Redirect + flash error "Gagal membuat backup"
    else Proses berhasil
        P-->>C: Output SQL lengkap
        C->>FS: Simpan file backup_{timestamp}.sql
        FS-->>C: OK
        C->>DB: Catat Activity Log "backup"
        C-->>B: Redirect + flash success "Backup berhasil dibuat"
    end
    B-->>A: Tampilkan daftar file backup terbaru
```

### 3.9 Guru Menilai Esai Kuis Secara Manual

```mermaid
sequenceDiagram
    actor G as Guru
    participant B as Browser
    participant C as QuizController (Guru)
    participant DB as Database
    participant GS as GradeService

    G->>B: Buka hasil kuis, lihat jawaban esai siswa
    B->>C: GET /guru/quizzes/{quiz}/attempts/{attempt}/review
    C->>DB: Ambil QuizAttempt + jawaban + soal
    DB-->>C: Data jawaban esai
    C-->>B: Tampilkan form input skor esai
    G->>B: Input skor tiap jawaban esai, submit
    B->>C: PUT /guru/quizzes/{quiz}/attempts/{attempt}/score
    loop Untuk setiap jawaban esai yang dinilai
        C->>C: score = min(input_guru, skor_maksimal_soal)
        C->>DB: Update QuizAnswer.score
    end
    C->>DB: Update QuizAttempt (score = total, status = graded)
    C->>GS: recalculateForCourse(quiz.course)
    GS->>DB: Hitung ulang rata-rata & simpan Grade
    DB-->>GS: OK
    C-->>B: Redirect + flash "Nilai essay berhasil disimpan"
```

## 4. Class Diagram

Diturunkan langsung dari 28 Model Eloquent di `app/Models/`. Karena seluruh kelas sekaligus akan terlalu padat untuk dibaca, class diagram dipecah menjadi tiga modul yang sejalan dengan pengelompokan entitas pada ERD (bagian 5). Atribut mengikuti `$fillable` masing-masing model; method yang ditampilkan hanya method bisnis publik yang bermakna (bukan relasi `belongsTo`/`hasMany` bawaan Eloquent, karena relasi tersebut sudah direpresentasikan oleh garis panah antarkelas).

### 4.1 Class Diagram — Modul Data Master & Akademik

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string password
        +string phone
        +string address
        +string gender
        +string avatar
        +bool is_active
        +datetime last_login_at
        +isAdmin() bool
        +isTeacher() bool
        +isStudent() bool
        +getAvatarUrlAttribute() string
    }
    class Teacher {
        +int id
        +int user_id
        +string nip
        +string nuptk
        +string birth_place
        +date birth_date
        +string employment_status
    }
    class Student {
        +int id
        +int user_id
        +string nisn
        +string nis
        +int class_id
        +int department_id
        +string birth_place
        +date birth_date
        +string parent_name
        +string parent_phone
        +string admission_year
        +string status
    }
    class Department {
        +int id
        +string name
        +string code
        +string description
    }
    class AcademicYear {
        +int id
        +string name
        +string semester
        +date start_date
        +date end_date
        +bool is_active
        +getLabelAttribute() string
    }
    class Subject {
        +int id
        +string name
        +string code
        +string description
    }
    class ClassRoom {
        +int id
        +string name
        +string grade_level
        +int department_id
        +int academic_year_id
        +int homeroom_teacher_id
    }
    class Course {
        +int id
        +int subject_id
        +int teacher_id
        +int class_id
        +int academic_year_id
        +getNameAttribute() string
    }
    class Enrollment {
        +int id
        +int student_id
        +int course_id
        +datetime enrolled_at
        +string status
    }
    class Schedule {
        +int id
        +int class_id
        +int course_id
        +int academic_year_id
        +string day_of_week
        +time start_time
        +time end_time
        +string room
    }

    User "1" --> "0..1" Teacher : punya profil
    User "1" --> "0..1" Student : punya profil
    Department "1" --> "0..*" ClassRoom : memiliki
    Department "1" --> "0..*" Student : menaungi
    AcademicYear "1" --> "0..*" ClassRoom : berlaku pada
    AcademicYear "1" --> "0..*" Course : berlaku pada
    AcademicYear "1" --> "0..*" Schedule : berlaku pada
    Teacher "1" --> "0..*" Course : mengajar
    Teacher "1" --> "0..*" ClassRoom : wali kelas
    ClassRoom "1" --> "0..*" Student : berisi
    ClassRoom "1" --> "0..*" Course : memiliki
    ClassRoom "1" --> "0..*" Schedule : memiliki
    Subject "1" --> "0..*" Course : diampu dalam
    Course "1" --> "0..*" Enrollment : memiliki
    Course "1" --> "0..*" Schedule : dijadwalkan
    Student "1" --> "0..*" Enrollment : mendaftar
```

### 4.2 Class Diagram — Modul Pembelajaran & Penilaian

> Kelas `Course`, `Teacher`, `Student`, dan `Schedule` pada diagram ini adalah kelas yang sama dengan Modul Data Master & Akademik (4.1) — ditampilkan tanpa atribut di sini agar relasi tetap terbaca, detail lengkapnya lihat 4.1.

```mermaid
classDiagram
    class Course
    class Teacher
    class Student
    class Schedule

    class Material {
        +int id
        +int course_id
        +int teacher_id
        +string title
        +string description
        +string type
        +string file_path
        +string file_name
        +int file_size
        +string video_url
        +bool is_published
        +getFileSizeForHumansAttribute() string
    }
    class Assignment {
        +int id
        +int course_id
        +int teacher_id
        +string title
        +string description
        +string attachment_path
        +datetime deadline
        +int max_score
        +bool allow_late
        +bool is_published
        +isPastDeadline() bool
    }
    class Submission {
        +int id
        +int assignment_id
        +int student_id
        +string file_path
        +string file_name
        +string note
        +datetime submitted_at
        +decimal score
        +string feedback
        +int graded_by
        +datetime graded_at
        +string status
    }
    class Quiz {
        +int id
        +int course_id
        +int teacher_id
        +string title
        +string description
        +int duration_minutes
        +datetime start_time
        +datetime end_time
        +bool shuffle_questions
        +bool show_result_immediately
        +bool is_published
        +isOpen() bool
        +totalScore() int
    }
    class QuizQuestion {
        +int id
        +int quiz_id
        +string type
        +string question
        +string option_a
        +string option_b
        +string option_c
        +string option_d
        +string option_e
        +string correct_option
        +int score
        +int order
        +options() array
    }
    class QuizAttempt {
        +int id
        +int quiz_id
        +int student_id
        +datetime started_at
        +datetime submitted_at
        +decimal score
        +string status
    }
    class QuizAnswer {
        +int id
        +int quiz_attempt_id
        +int quiz_question_id
        +string selected_option
        +string answer_text
        +decimal score
        +bool is_correct
    }
    class Exam {
        +int id
        +int course_id
        +int teacher_id
        +string title
        +string description
        +int duration_minutes
        +datetime start_time
        +datetime end_time
        +bool shuffle_questions
        +int questions_to_show
        +int passing_score
        +bool is_published
        +isOpen() bool
        +totalScore() int
    }
    class ExamQuestion {
        +int id
        +int exam_id
        +string type
        +string question
        +string option_a
        +string option_b
        +string option_c
        +string option_d
        +string option_e
        +string correct_option
        +int score
        +int order
        +options() array
    }
    class ExamAttempt {
        +int id
        +int exam_id
        +int student_id
        +datetime started_at
        +datetime submitted_at
        +decimal score
        +bool is_passed
        +string status
    }
    class ExamAnswer {
        +int id
        +int exam_attempt_id
        +int exam_question_id
        +string selected_option
        +string answer_text
        +decimal score
        +bool is_correct
    }
    class Grade {
        +int id
        +int student_id
        +int course_id
        +int academic_year_id
        +decimal assignment_score
        +decimal quiz_score
        +decimal exam_score
        +decimal final_score
        +string letter_grade
        +string notes
        +letterFor(score) string$
    }
    class Attendance {
        +int id
        +int schedule_id
        +int student_id
        +int recorded_by
        +date date
        +string status
        +string note
    }

    Course "1" --> "0..*" Material : memiliki
    Teacher "1" --> "0..*" Material : mengunggah
    Course "1" --> "0..*" Assignment : memiliki
    Teacher "1" --> "0..*" Assignment : membuat
    Assignment "1" --> "0..*" Submission : menerima
    Student "1" --> "0..*" Submission : mengumpulkan
    Course "1" --> "0..*" Quiz : memiliki
    Quiz "1" --> "0..*" QuizQuestion : memiliki
    Quiz "1" --> "0..*" QuizAttempt : memiliki
    Student "1" --> "0..*" QuizAttempt : mengerjakan
    QuizAttempt "1" --> "0..*" QuizAnswer : berisi
    QuizQuestion "1" --> "0..*" QuizAnswer : dijawab pada
    Course "1" --> "0..*" Exam : memiliki
    Exam "1" --> "0..*" ExamQuestion : memiliki
    Exam "1" --> "0..*" ExamAttempt : memiliki
    Student "1" --> "0..*" ExamAttempt : mengerjakan
    ExamAttempt "1" --> "0..*" ExamAnswer : berisi
    ExamQuestion "1" --> "0..*" ExamAnswer : dijawab pada
    Student "1" --> "0..*" Grade : memiliki
    Course "1" --> "0..*" Grade : memiliki
    Schedule "1" --> "0..*" Attendance : mencatat
    Student "1" --> "0..*" Attendance : dicatat
```

### 4.3 Class Diagram — Modul Komunikasi & Sistem

> Kelas `User` dan `Course` pada diagram ini sama dengan yang ada di 4.1 — ditampilkan tanpa atribut agar relasi tetap terbaca.

```mermaid
classDiagram
    class User
    class Course

    class Announcement {
        +int id
        +int user_id
        +string title
        +string content
        +string type
        +int class_id
        +int course_id
        +bool is_published
        +datetime published_at
    }
    class Discussion {
        +int id
        +int course_id
        +int user_id
        +string title
        +string body
        +bool is_locked
    }
    class DiscussionComment {
        +int id
        +int discussion_id
        +int user_id
        +int parent_id
        +string body
        +string attachment_path
    }
    class Setting {
        +string key
        +string value
        +string group
        +get(key, default) mixed$
        +set(key, value, group) void$
    }
    class ActivityLog {
        +int id
        +int user_id
        +string action
        +string description
        +string subject_type
        +int subject_id
        +array properties
        +string ip_address
        +string user_agent
    }

    User "1" --> "0..*" Announcement : membuat
    User "1" --> "0..*" Discussion : membuat
    Course "1" --> "0..*" Discussion : memiliki
    Discussion "1" --> "0..*" DiscussionComment : memiliki
    User "1" --> "0..*" DiscussionComment : menulis
    DiscussionComment "1" --> "0..*" DiscussionComment : membalas (parent_id)
    User "1" --> "0..*" ActivityLog : melakukan
    ActivityLog "0..1" ..> "1" User : subject polymorphic (mis. saat admin diaudit)
```
