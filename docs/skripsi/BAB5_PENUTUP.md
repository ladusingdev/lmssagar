# BAB V PENUTUP

## 5.1 Kesimpulan

Berdasarkan hasil penelitian dan pembahasan pada Bab IV, dapat disimpulkan hal-hal berikut, mengacu langsung pada rumusan masalah yang ditetapkan pada Bab I:

1. **Analisis kebutuhan** sistem LMS SMKN 9 Garut berhasil dirumuskan ke dalam 27 kebutuhan fungsional (10 untuk Administrator, 9 untuk Guru, 8 untuk Siswa) dan 7 kebutuhan nonfungsional yang mencakup aspek *usability, security, performance, reliability, maintainability, portability,* dan *auditability*, sebagaimana diuraikan pada subbab 4.1.

2. **Perancangan sistem** berhasil menghasilkan Entity Relationship Diagram dengan 30 entitas domain aplikasi beserta aturan integritas referensial (`CASCADE`, `RESTRICT`, `SET NULL`) yang disesuaikan dengan makna bisnis tiap relasi, serta diagram UML (1 Use Case Diagram, 7 Activity Diagram, 9 Sequence Diagram, dan 3 Class Diagram per modul yang mencakup seluruh 28 kelas Model Eloquent) yang memodelkan interaksi dan struktur objek sistem, sebagaimana diuraikan pada subbab 4.2.

3. **Implementasi sistem** berhasil dilakukan menggunakan framework Laravel 12 dan PHP 8.2, menghasilkan sistem yang berjalan nyata dengan 49 controller, 28 model, dan 32 berkas migrasi basis data, serta menerapkan prinsip keamanan aplikasi web meliputi proteksi CSRF, XSS, SQL Injection, *hashing* kata sandi, *rate limiting* login, dan Role-Based Access Control tiga peran (Administrator, Guru, Siswa) melalui paket Spatie Laravel-Permission, sebagaimana diuraikan pada subbab 4.3.

4. **Pengujian black-box** yang dilaksanakan terhadap 13 kasus uji representatif — mencakup autentikasi dan otorisasi, integritas data pada level basis data, akurasi logika penilaian otomatis pada skala data nyata (600 jawaban kuis dan 590 baris nilai), serta kepatuhan terhadap aturan validasi dan kepemilikan data — menunjukkan **tingkat keberhasilan 100% (13 dari 13 kasus uji valid)** pada kondisi akhir sistem. Proses pengujian turut menemukan dan berhasil memperbaiki satu cacat fungsional (penghapusan guru dengan mata pelajaran aktif yang sebelumnya menyebabkan *error* server), membuktikan bahwa tahap evaluasi dalam kerangka DDR berperan nyata sebagai mekanisme umpan balik perbaikan produk, sebagaimana diuraikan pada subbab 4.4.1 dan 4.5.

5. **Tingkat penerimaan pengguna** terhadap sistem LMS yang dikembangkan — *[⚠️ TODO: lengkapi kalimat ini setelah data kuesioner UAT pada subbab 4.4.2 terisi, contoh: "menunjukkan kategori 'Sangat Layak' dengan persentase kelayakan rata-rata XX,X% berdasarkan evaluasi terhadap N responden (Administrator, Guru, dan Siswa)."]*

> ⚠️ **TODO (Anda lengkapi):** Poin 5 di atas **wajib** diisi berdasarkan hasil kuesioner UAT sungguhan (subbab 4.4.2) — jangan disimpulkan sebelum data terkumpul dan diolah. Jika ternyata ada aspek yang dinilai kurang oleh responden, sebutkan juga secara jujur sebagai bagian dari kesimpulan (kejujuran akademik terhadap hasil negatif/tidak sesuai justru memperkuat validitas skripsi).

Secara umum, penelitian ini membuktikan bahwa pendekatan **Design and Development Research (DDR)** dapat diterapkan secara efektif dalam pengembangan sistem informasi pendidikan, karena menyediakan kerangka kerja yang memastikan setiap tahap (Analysis, Design, Development, Evaluation) menghasilkan luaran yang terdokumentasi dan dapat diverifikasi, sekaligus menempatkan pengujian dan evaluasi pengguna sebagai bagian integral dari proses penelitian — bukan sekadar tahap akhir pengembangan perangkat lunak konvensional.

## 5.2 Saran

Berdasarkan hasil penelitian, keterbatasan yang ditemukan, dan batasan masalah pada subbab 1.5, berikut saran untuk pemanfaatan dan pengembangan lebih lanjut:

### 5.2.1 Bagi SMKN 9 Garut

1. Sistem LMS ini disarankan diimplementasikan secara bertahap, dimulai dari pelatihan penggunaan bagi Administrator dan Guru sebelum digunakan penuh oleh seluruh siswa, agar transisi dari proses manual ke digital berjalan lancar.
2. Diperlukan kebijakan pencadangan (*backup*) basis data secara berkala menggunakan fitur Backup & Restore yang telah tersedia pada modul Administrator, untuk mengantisipasi risiko kehilangan data akademik.

### 5.2.2 Bagi Pengembangan Sistem Selanjutnya

1. Pengujian pada penelitian ini masih berfokus pada 13 kasus uji representatif; penelitian/pengembangan lanjutan disarankan memperluas cakupan pengujian black-box hingga mencakup seluruh 27 kebutuhan fungsional secara menyeluruh, termasuk pengujian ekspor laporan (PDF/Excel), fitur backup/restore, dan alur forum diskusi secara end-to-end.
2. Sistem saat ini belum mencakup aplikasi mobile native; pengembangan lanjutan dapat mengeksplorasi penyediaan REST API agar dapat dikonsumsi oleh aplikasi mobile Android/iOS di masa mendatang.
3. Modul notifikasi saat ini terbatas pada notifikasi dalam sistem (*database notification*); pengembangan lanjutan dapat mengintegrasikan notifikasi push browser atau surel (*email*) agar informasi penting (tugas baru, nilai baru) lebih cepat sampai kepada pengguna.
4. Evaluasi penerimaan pengguna pada penelitian ini dilakukan dalam skala terbatas *(sesuai jumlah responden pada subbab 3.3)*; penelitian lanjutan disarankan melakukan evaluasi dalam skala lebih luas dan dalam jangka waktu penggunaan yang lebih panjang (mis. satu semester penuh) untuk mengukur dampak nyata terhadap efisiensi proses akademik, bukan hanya persepsi awal pengguna.

### 5.2.3 Bagi Peneliti Selanjutnya

Penelitian ini dapat dijadikan rujukan bagi penelitian sejenis yang ingin menerapkan metode **Design and Development Research (DDR)** pada pengembangan sistem informasi berbasis web, khususnya di lingkungan pendidikan menengah kejuruan. Peneliti selanjutnya disarankan mempertimbangkan penambahan tahap **Dissemination** (penyebarluasan) di luar empat tahap DDR yang digunakan pada penelitian ini, apabila tujuan penelitian juga mencakup adopsi sistem di sekolah lain yang memiliki karakteristik serupa.
