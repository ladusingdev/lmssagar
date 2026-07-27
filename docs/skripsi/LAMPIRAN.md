# LAMPIRAN

## Lampiran A — Instrumen Kuesioner User Acceptance Testing (UAT)

**Petunjuk pengisian:** Berilah tanda centang (✓) pada kolom yang sesuai dengan pendapat Anda terhadap Sistem LMS SMKN 9 Garut. Skala: 1 = Sangat Tidak Setuju, 2 = Tidak Setuju, 3 = Cukup/Netral, 4 = Setuju, 5 = Sangat Setuju.

> ⚠️ **TODO (Anda lengkapi):** Perbanyak (fotokopi/cetak, atau salin ke Google Form) sesuai jumlah responden yang ditetapkan pada `BAB3_METODE_PENELITIAN.md` subbab 3.3, sebarkan ke responden asli, lalu rekap hasilnya ke tabel pada `BAB4_HASIL_PEMBAHASAN.md` subbab 4.4.2.

### A.1 Kuesioner untuk Administrator

| No | Aspek | Pernyataan | 1 | 2 | 3 | 4 | 5 |
|---|---|---|---|---|---|---|---|
| 1 | Usability | Menu dan navigasi pada panel Administrator mudah dipahami | | | | | |
| 2 | Usability | Tampilan sistem nyaman dilihat dan konsisten di setiap halaman | | | | | |
| 3 | Kesesuaian Fungsi | Fitur pengelolaan data Guru/Siswa/Jurusan/Kelas/Mapel berfungsi sesuai kebutuhan | | | | | |
| 4 | Kesesuaian Fungsi | Fitur ekspor laporan (PDF/Excel) membantu pekerjaan administrasi | | | | | |
| 5 | Kesesuaian Fungsi | Fitur backup/restore basis data mudah digunakan dan dapat diandalkan | | | | | |
| 6 | Reliability | Sistem menampilkan pesan yang jelas ketika terjadi kesalahan input | | | | | |
| 7 | Security | Saya merasa data akademik yang dikelola sistem ini aman | | | | | |
| 8 | Performance | Sistem merespons dengan cepat saat digunakan | | | | | |
| 9 | Auditability | Fitur log aktivitas membantu memantau perubahan data oleh pengguna lain | | | | | |
| 10 | Kepuasan Umum | Secara keseluruhan saya puas menggunakan sistem ini untuk pekerjaan administrasi sekolah | | | | | |

### A.2 Kuesioner untuk Guru

| No | Aspek | Pernyataan | 1 | 2 | 3 | 4 | 5 |
|---|---|---|---|---|---|---|---|
| 1 | Usability | Menu dan navigasi pada panel Guru mudah dipahami | | | | | |
| 2 | Kesesuaian Fungsi | Fitur unggah materi pembelajaran (PDF/Word/PPT/Video/Gambar) mudah digunakan | | | | | |
| 3 | Kesesuaian Fungsi | Fitur pembuatan tugas dan penilaian membantu proses pemberian nilai | | | | | |
| 4 | Kesesuaian Fungsi | Fitur pembuatan kuis/ujian daring (pilihan ganda & esai) sesuai kebutuhan mengajar saya | | | | | |
| 5 | Kesesuaian Fungsi | Penilaian otomatis untuk soal pilihan ganda menghemat waktu koreksi saya | | | | | |
| 6 | Kesesuaian Fungsi | Fitur presensi digital lebih praktis dibanding presensi kertas | | | | | |
| 7 | Reliability | Rekap nilai otomatis yang ditampilkan sistem akurat dan dapat dipercaya | | | | | |
| 8 | Performance | Sistem merespons dengan cepat saat mengunggah materi/membuat soal | | | | | |
| 9 | Security | Saya merasa data nilai yang saya kelola tidak dapat diubah oleh pihak yang tidak berwenang | | | | | |
| 10 | Kepuasan Umum | Secara keseluruhan saya puas dan terbantu menggunakan sistem ini dalam mengajar | | | | | |

### A.3 Kuesioner untuk Siswa

| No | Aspek | Pernyataan | 1 | 2 | 3 | 4 | 5 |
|---|---|---|---|---|---|---|---|
| 1 | Usability | Menu dan navigasi pada panel Siswa mudah dipahami | | | | | |
| 2 | Kesesuaian Fungsi | Saya mudah menemukan dan mengunduh materi pembelajaran | | | | | |
| 3 | Kesesuaian Fungsi | Proses mengumpulkan tugas melalui sistem ini mudah dilakukan | | | | | |
| 4 | Kesesuaian Fungsi | Tampilan timer dan proses pengerjaan kuis/ujian daring jelas dan tidak membingungkan | | | | | |
| 5 | Reliability | Hasil kuis/ujian yang ditampilkan sistem sesuai dengan jawaban yang saya berikan | | | | | |
| 6 | Kesesuaian Fungsi | Saya mudah melihat rekap nilai, presensi, dan jadwal pelajaran saya sendiri | | | | | |
| 7 | Kesesuaian Fungsi | Fitur forum diskusi dan pengumuman membantu komunikasi dengan guru/sekolah | | | | | |
| 8 | Performance | Sistem tetap responsif saat saya mengerjakan kuis/ujian dengan batas waktu | | | | | |
| 9 | Usability | Sistem nyaman diakses melalui perangkat mobile (HP/tablet) | | | | | |
| 10 | Kepuasan Umum | Secara keseluruhan saya puas menggunakan sistem ini untuk belajar | | | | | |

### A.4 Rekap Skor (diisi setelah kuesioner terkumpul)

| Peran | Jumlah Responden | Jumlah Butir | Skor Maksimal Ideal (5 × butir × responden) | Total Skor Diperoleh | Persentase | Kategori |
|---|---|---|---|---|---|---|
| Administrator | | 10 | | | | |
| Guru | | 10 | | | | |
| Siswa | | 10 | | | | |
| **Rata-rata Keseluruhan** | | | | | | |

Rumus dan kategori interpretasi persentase mengacu pada `BAB3_METODE_PENELITIAN.md` subbab 3.7.2.

---

## Lampiran B — Reproduksi Pengujian Black-Box Otomatis (TC-01 s.d. TC-07)

Kasus uji TC-01–TC-07 pada `BAB4_HASIL_PEMBAHASAN.md` subbab 4.4.1 dieksekusi menggunakan skrip PowerShell yang mensimulasikan permintaan HTTP nyata (login, akses halaman terproteksi, penghapusan data, logout) lengkap dengan penanganan token CSRF dan sesi. Skrip dan hasil mentah tersedia pada:

- Skrip: [`lampiran/blackbox_test.ps1`](lampiran/blackbox_test.ps1)
- Hasil mentah (JSON, dieksekusi 22 Juli 2026): [`lampiran/blackbox_results.json`](lampiran/blackbox_results.json)

**Cara menjalankan ulang** (syarat: XAMPP Apache & MySQL aktif, aplikasi dapat diakses di `http://localhost/LMS9/public`):

```powershell
powershell -ExecutionPolicy Bypass -File "docs\skripsi\lampiran\blackbox_test.ps1"
```

### Reproduksi TC-08 (Konstrain Unik Penugasan Mengajar)

```sql
-- Dijalankan via: C:\xampp\mysql\bin\mysql.exe -u root lms_smkn9garut
INSERT INTO courses (subject_id, teacher_id, class_id, academic_year_id, created_at, updated_at)
VALUES (2, 5, 1, 2, NOW(), NOW());
-- Hasil nyata: ERROR 1062 (23000): Duplicate entry '2-1-2' for key 'courses_unique_assignment'
```

### Reproduksi TC-09 (Konsistensi Penilaian Otomatis Kuis)

```sql
SELECT
    COUNT(*) AS total_mc_answers,
    SUM(CASE WHEN (qa.selected_option = qq.correct_option) != qa.is_correct THEN 1 ELSE 0 END) AS inkonsisten_is_correct,
    SUM(CASE WHEN qa.is_correct = 1 AND qa.score != qq.score THEN 1 ELSE 0 END) AS inkonsisten_skor_benar,
    SUM(CASE WHEN qa.is_correct = 0 AND qa.score != 0 THEN 1 ELSE 0 END) AS inkonsisten_skor_salah
FROM quiz_answers qa
JOIN quiz_questions qq ON qq.id = qa.quiz_question_id
WHERE qq.type = 'multiple_choice';
-- Hasil nyata: total_mc_answers=600, seluruh kolom inkonsisten=0
```

### Reproduksi TC-10 (Konsistensi Huruf Mutu)

```sql
SELECT
    COUNT(*) AS total_grades,
    SUM(CASE WHEN final_score IS NOT NULL AND (
        (final_score >= 90 AND letter_grade != 'A') OR
        (final_score >= 80 AND final_score < 90 AND letter_grade != 'B') OR
        (final_score >= 70 AND final_score < 80 AND letter_grade != 'C') OR
        (final_score >= 60 AND final_score < 70 AND letter_grade != 'D') OR
        (final_score < 60 AND letter_grade != 'E')
    ) THEN 1 ELSE 0 END) AS inkonsisten_letter_grade
FROM grades;
-- Hasil nyata: total_grades=590, inkonsisten_letter_grade=0
```

---

## Lampiran C — Checklist Tangkapan Layar (Screenshot) untuk Bab IV

> ⚠️ **TODO (Anda lengkapi):** Ambil tangkapan layar berikut melalui browser (login menggunakan akun contoh pada `docs/INSTALLATION.md`), lalu sisipkan ke Bab IV versi Word/Docs dengan penomoran "Gambar 4.1", "Gambar 4.2", dst., dan beri keterangan di bawah tiap gambar.

- [ ] Halaman login
- [ ] Dashboard Administrator (statistik ringkasan)
- [ ] Halaman kelola data Guru (index + form tambah)
- [ ] Halaman kelola penugasan mengajar (Courses)
- [ ] Contoh laporan hasil ekspor PDF
- [ ] Contoh laporan hasil ekspor Excel
- [ ] Dashboard Guru
- [ ] Form unggah materi pembelajaran
- [ ] Form pembuatan kuis + soal pilihan ganda & esai
- [ ] Halaman presensi (input oleh Guru)
- [ ] Dashboard Siswa
- [ ] Halaman daftar materi (unduh)
- [ ] Halaman pengerjaan kuis/ujian (dengan timer terlihat)
- [ ] Halaman hasil kuis/ujian (skor otomatis)
- [ ] Halaman rekap nilai & presensi milik siswa
- [ ] Halaman forum diskusi
- [ ] Pesan error yang ditampilkan sistem saat percobaan hapus guru dengan mata pelajaran aktif (bukti TC-06, subbab 4.3.5)

---

## Lampiran D — Kode Program Kunci (Kutipan)

Kutipan kode berikut dirujuk langsung pada Bab IV sebagai bukti implementasi. Kode lengkap tersedia di repositori proyek (`app/`, `database/migrations/`).

### D.1 Perbaikan Bug Penghapusan Guru (`app/Http/Controllers/Admin/TeacherController.php`)

```php
public function destroy(Teacher $teacher): RedirectResponse
{
    if ($teacher->courses()->exists()) {
        return back()->with('error', 'Guru tidak dapat dihapus karena masih mengampu mata pelajaran. Pindahkan atau hapus mata pelajaran tersebut terlebih dahulu.');
    }

    DB::transaction(function () use ($teacher) {
        ActivityLogger::log('delete', "Menghapus guru: {$teacher->user->name}");
        $teacher->user->delete();
    });

    return back()->with('success', 'Data guru berhasil dihapus.');
}
```

### D.2 Penilaian Otomatis Kuis (`app/Http/Controllers/Siswa/QuizController.php`)

```php
public function answer(Request $request, Quiz $quiz): JsonResponse
{
    // ...
    $isCorrect = $question->type === 'multiple_choice'
        ? ($data['selected_option'] ?? null) === $question->correct_option
        : null;

    QuizAnswer::updateOrCreate(
        ['quiz_attempt_id' => $attempt->id, 'quiz_question_id' => $question->id],
        [
            'selected_option' => $data['selected_option'] ?? null,
            'answer_text' => $data['answer_text'] ?? null,
            'is_correct' => $isCorrect,
            'score' => $question->type === 'multiple_choice' ? ($isCorrect ? $question->score : 0) : null,
        ]
    );
    // ...
}
```

### D.3 Rekap Nilai Otomatis (`app/Services/GradeService.php`)

```php
public static function recalculateForCourse(Course $course): void
{
    // rata-rata skor tugas, kuis, ujian per siswa yang tersedia (nilai kosong diabaikan),
    // dipetakan menjadi nilai akhir dan huruf mutu (A/B/C/D/E)
}
```

---

## Lampiran E — Akun Uji Coba Sistem

| Peran | Email | Kata Sandi |
|---|---|---|
| Administrator | admin@smkn9garut.sch.id | password |
| Guru (contoh) | guru1@smkn9garut.sch.id s.d. guru10@smkn9garut.sch.id | password |
| Siswa (contoh) | siswa1@smkn9garut.sch.id s.d. siswa100@smkn9garut.sch.id | password |

> ⚠️ **Catatan keamanan:** Kata sandi contoh di atas hanya untuk lingkungan pengembangan/demo skripsi. **Ganti seluruh kata sandi default** apabila sistem akan digunakan pada data akademik sungguhan di SMKN 9 Garut.
