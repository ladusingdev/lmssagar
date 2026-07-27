# BAB II TINJAUAN PUSTAKA

> ⚠️ **TODO (Anda lengkapi):** Seluruh sitasi (Penulis, Tahun) pada bab ini merujuk pada konsep/buku/sumber yang secara umum dikenal luas di bidang Sistem Informasi dan Rekayasa Perangkat Lunak. Sebelum sidang, **cek kembali setiap sitasi** terhadap edisi buku/artikel yang benar-benar Anda akses (via perpustakaan kampus, Google Scholar, atau Garuda), lalu sesuaikan nomor halaman/edisi di footnote atau body text jika program studi Anda mewajibkannya. Jangan menyalin sitasi ini tanpa verifikasi.

## 2.1 Kajian Teori

### 2.1.1 Sistem Informasi

Sistem informasi adalah kombinasi teratur dari manusia, perangkat keras, perangkat lunak, jaringan komunikasi, sumber data, kebijakan, dan prosedur yang bertujuan menyimpan, mengambil, mengubah, dan menyebarkan informasi dalam sebuah organisasi (Laudon & Laudon, 2020). Dalam konteks pendidikan, sistem informasi berperan mendukung proses pengambilan keputusan, koordinasi, dan pengendalian aktivitas akademik, sekaligus membantu guru, siswa, dan staf administrasi dalam menganalisis permasalahan, memvisualisasikan hal-hal kompleks, dan menciptakan produk baru berupa layanan digital.

### 2.1.2 Learning Management System (LMS) dan E-Learning

*E-learning* merupakan proses pembelajaran yang memanfaatkan teknologi elektronik, khususnya internet, sebagai media penyampaian materi, interaksi, dan evaluasi pembelajaran (Clark & Mayer, 2016). *Learning Management System* (LMS) adalah perangkat lunak yang digunakan untuk merencanakan, melaksanakan, dan menilai proses pembelajaran tertentu, umumnya menyediakan fasilitas bagi pengajar untuk mengelola kelas, materi ajar, dan penilaian, sekaligus fasilitas bagi peserta didik untuk mengakses materi dan mengikuti evaluasi secara daring.

Secara umum, fitur inti sebuah LMS mencakup: (1) manajemen pengguna dan peran (*role management*), (2) manajemen konten/materi pembelajaran, (3) manajemen penugasan dan pengumpulan tugas, (4) evaluasi/penilaian (kuis dan ujian dengan penilaian otomatis untuk soal objektif), (5) pelaporan dan analitik capaian belajar, serta (6) komunikasi antarpengguna (pengumuman, forum diskusi, notifikasi). Sistem LMS SMKN 9 Garut yang dikembangkan pada penelitian ini mengadopsi keenam komponen tersebut, ditambah modul presensi dan jadwal pelajaran yang relevan dengan konteks sekolah menengah kejuruan di Indonesia.

### 2.1.3 Rekayasa Perangkat Lunak dan Model Pengembangan Sistem

Rekayasa perangkat lunak (*software engineering*) adalah disiplin ilmu yang membahas seluruh aspek produksi perangkat lunak, mulai dari tahap awal spesifikasi sistem hingga pemeliharaan sistem setelah digunakan (Pressman & Maxim, 2020; Sommerville, 2016). Berbagai model pengembangan perangkat lunak telah dikenal luas, di antaranya model *Waterfall* yang bersifat linear-sekuensial (analisis, desain, implementasi, pengujian, pemeliharaan), model *Prototyping* yang menekankan iterasi purwarupa bersama pengguna, serta model *Agile* yang menekankan pengembangan inkremental dan kolaboratif. Penelitian ini tidak menggunakan model SDLC murni tersebut, melainkan kerangka **Design and Development Research (DDR)** — dijelaskan pada subbab 2.1.9 — yang memosisikan pengembangan sistem sebagai bagian dari proses penelitian ilmiah yang menghasilkan bukti empiris atas kelayakan produk.

### 2.1.4 Framework Laravel

Laravel adalah *framework* aplikasi web berbasis bahasa pemrograman PHP yang bersifat *open-source*, menggunakan pola arsitektur *Model-View-Controller* (MVC) untuk memisahkan logika data (Model), tampilan (View), dan logika kendali aplikasi (Controller) (Otwell, 2024; dokumentasi resmi laravel.com). Laravel menyediakan berbagai komponen bawaan yang mempercepat pengembangan, di antaranya *Eloquent ORM* untuk interaksi basis data berorientasi objek, *Blade templating engine* untuk penyusunan tampilan, sistem migrasi basis data (*migrations*) untuk mengelola skema secara terversi, serta mekanisme keamanan bawaan seperti proteksi CSRF (*Cross-Site Request Forgery*) dan *query binding* yang mencegah SQL Injection. Penelitian ini menggunakan **Laravel 12** dengan PHP 8.2, sebagaimana dispesifikasikan pada `composer.json` proyek.

Selain Laravel inti, penelitian ini memanfaatkan beberapa paket (*package*) pendukung dalam ekosistem Laravel:

- **Laravel Fortify** — menyediakan implementasi backend autentikasi (login, registrasi, manajemen profil, reset password) tanpa mendikte tampilan antarmuka, sehingga tampilan dapat disesuaikan dengan tema sekolah.
- **Spatie Laravel-Permission** — menyediakan implementasi *Role-Based Access Control* (RBAC) untuk mengatur hak akses berdasarkan peran (Administrator, Guru, Siswa) pada level middleware dan otorisasi.
- **barryvdh/laravel-dompdf** — menyediakan kemampuan pembuatan dokumen PDF dari tampilan HTML (Blade view) untuk kebutuhan cetak laporan.
- **maatwebsite/excel** — menyediakan kemampuan ekspor data ke format Microsoft Excel (.xlsx).

### 2.1.5 Basis Data dan Entity Relationship Diagram (ERD)

Basis data (*database*) adalah kumpulan data yang terorganisasi sedemikian rupa sehingga dapat diakses, dikelola, dan diperbarui dengan mudah (Connolly & Begg, 2015). *Entity Relationship Diagram* (ERD) adalah notasi grafis yang digunakan untuk memodelkan entitas data beserta relasi antarentitas dalam suatu sistem, umumnya digunakan pada tahap perancangan basis data sebelum diimplementasikan ke dalam *Data Definition Language* (DDL) suatu *Database Management System* (DBMS) (Elmasri & Navathe, 2016). Penelitian ini menggunakan **MySQL/MariaDB** sebagai DBMS, dengan skema basis data yang dirancang dalam bentuk ERD mencakup 30 tabel (lihat Bab IV dan `docs/ERD.md`), serta menerapkan integritas referensial melalui *foreign key constraint* dengan tiga jenis aksi hapus (`CASCADE`, `RESTRICT`, dan `SET NULL`) yang disesuaikan dengan makna bisnis masing-masing relasi.

### 2.1.6 Unified Modeling Language (UML)

*Unified Modeling Language* (UML) adalah bahasa permodelan standar yang digunakan untuk menspesifikasikan, memvisualisasikan, membangun, dan mendokumentasikan artefak dari sistem perangkat lunak (Booch, Rumbaugh, & Jacobson, dikutip dalam Sukamto & Shalahuddin, 2018). Diagram UML yang digunakan dalam penelitian ini meliputi:

- **Use Case Diagram** — menggambarkan interaksi antara aktor (Administrator, Guru, Siswa) dengan fungsionalitas sistem.
- **Activity Diagram** — menggambarkan alur kerja/proses bisnis suatu fitur secara prosedural, misalnya alur siswa mengerjakan ujian daring.
- **Sequence Diagram** — menggambarkan interaksi antarobjek/komponen sistem berdasarkan urutan waktu, misalnya alur autentikasi pengguna hingga pengalihan (*redirect*) berdasarkan peran.
- **Class Diagram** — menggambarkan struktur statis sistem berupa kelas, atribut, method, dan relasi antarkelas (asosiasi beserta *multiplicity*-nya) — pada penelitian ini diturunkan langsung dari Model Eloquent yang merepresentasikan setiap entitas basis data.

### 2.1.7 Role-Based Access Control (RBAC)

*Role-Based Access Control* adalah model kontrol akses yang membatasi akses sistem berdasarkan peran yang dimiliki pengguna dalam suatu organisasi, bukan berdasarkan identitas individu secara langsung (Ferraiolo, Kuhn, & Chandramouli, 2007). Setiap peran (*role*) memiliki himpunan hak akses (*permission*) tertentu, dan setiap pengguna diberi satu atau lebih peran. Pendekatan ini memudahkan pengelolaan hak akses pada sistem dengan banyak pengguna dan mengurangi risiko akses tidak sah terhadap data yang bukan menjadi kewenangannya. Pada penelitian ini, RBAC diimplementasikan menggunakan paket **Spatie Laravel-Permission**, dengan tiga peran utama: `admin`, `guru`, dan `siswa`, yang diterapkan pada level *middleware* (`role:`) di seluruh grup rute (*route group*) sistem.

### 2.1.8 Keamanan Aplikasi Web

Keamanan aplikasi web merupakan aspek krusial dalam pengembangan sistem berbasis web, khususnya sistem yang menyimpan data pribadi dan data akademik. Mengacu pada kerangka *OWASP Top 10* (OWASP Foundation, 2021), beberapa kerentanan umum yang perlu diantisipasi meliputi *Cross-Site Request Forgery* (CSRF), *Cross-Site Scripting* (XSS), dan *SQL Injection*. Penelitian ini menerapkan mitigasi terhadap ketiga kerentanan tersebut melalui mekanisme bawaan Laravel: token CSRF pada setiap form, *output escaping* otomatis pada templating Blade (`{{ }}`) untuk mencegah XSS, serta penggunaan *Eloquent ORM*/*Query Builder* dengan *parameter binding* untuk mencegah SQL Injection. Selain itu diterapkan *rate limiting* pada percobaan login (maksimum 5 kali per menit per kombinasi email dan alamat IP) untuk mengurangi risiko serangan *brute force*.

### 2.1.9 Black-Box Testing

*Black-box testing* adalah metode pengujian perangkat lunak yang berfokus pada spesifikasi fungsional sistem tanpa memerlukan pengetahuan tentang struktur internal atau kode program (Pressman & Maxim, 2020). Pengujian dilakukan dengan memberikan data masukan (*input*) pada suatu fungsi sistem, kemudian membandingkan keluaran (*output*) yang dihasilkan dengan hasil yang diharapkan (*expected result*). Jika keduanya sesuai, kasus uji (*test case*) dinyatakan **valid/sesuai**; jika tidak, dinyatakan **tidak sesuai** dan menjadi temuan yang perlu ditindaklanjuti. Metode ini dipilih dalam tahap evaluasi penelitian karena sesuai untuk menguji kesesuaian fungsional sistem terhadap kebutuhan yang telah dirumuskan pada tahap analisis (subbab 3.6 dan Bab IV).

### 2.1.10 Design and Development Research (DDR)

*Design and Development Research* (DDR) didefinisikan oleh Richey & Klein (2007) sebagai kajian sistematis terhadap proses perancangan, pengembangan, dan evaluasi, dengan tujuan membangun dasar empiris untuk penciptaan produk maupun model instruksional dan non-instruksional yang baru, ataupun yang telah ditingkatkan. Richey & Nelson (1996) membedakan DDR menjadi dua tipe:

- **Tipe 1 (Product and Tool Research)** — berfokus pada perancangan, pengembangan, dan evaluasi suatu produk atau program tertentu.
- **Tipe 2 (Model Research)** — berfokus pada perancangan, pengembangan, dan validasi model perancangan/pengembangan itu sendiri.

Penelitian ini termasuk **DDR Tipe 1**, karena berfokus pada perancangan, pengembangan, dan evaluasi satu produk konkret, yaitu sistem LMS SMKN 9 Garut, bukan pada pengembangan model pengembangan yang bersifat generik. Richey & Klein (2007) menguraikan tahapan DDR Tipe 1 ke dalam empat tahap besar — **Analysis, Design, Development, dan Evaluation** — yang pada penelitian ini dioperasionalkan sebagai berikut (rincian lengkap pada Bab III):

1. **Analysis** — analisis kebutuhan fungsional dan nonfungsional berdasarkan proses bisnis akademik yang berjalan.
2. **Design** — perancangan arsitektur sistem, basis data (ERD), dan alur proses (diagram UML).
3. **Development** — implementasi rancangan menjadi kode program yang dapat dieksekusi (Laravel 12 + MySQL).
4. **Evaluation** — pengujian fungsional (black-box testing) dan evaluasi penerimaan pengguna (User Acceptance Testing).

### 2.1.11 Skala Likert dan User Acceptance Testing

*User Acceptance Testing* (UAT) adalah tahap pengujian yang dilakukan oleh pengguna akhir (*end-user*) untuk menilai apakah sistem yang dibangun telah memenuhi kebutuhan dan dapat diterima untuk digunakan dalam kondisi nyata (Sommerville, 2016). Untuk mengukur tingkat penerimaan secara kuantitatif, penelitian ini menggunakan **Skala Likert** (Likert, 1932; dikutip dalam Sugiyono, 2019), yaitu skala yang mengukur sikap, pendapat, dan persepsi responden terhadap suatu objek dengan rentang jawaban bertingkat (umumnya 1–5, dari "Sangat Tidak Setuju" hingga "Sangat Setuju"). Hasil kuesioner diolah menggunakan perhitungan persentase kelayakan untuk menentukan kategori penerimaan sistem (lihat subbab 3.7).

## 2.2 Penelitian Terdahulu

Berikut kajian terhadap beberapa penelitian sejenis yang menjadi rujukan dan pembanding posisi penelitian ini:

| No | Judul Penelitian | Sumber | Metode | Persamaan | Perbedaan dengan Penelitian Ini |
|---|---|---|---|---|---|
| 1 | Pengembangan Learning Management System (LMS) Berbasis Web dengan Framework Laravel di SMP Negeri 1 Bareng | Repositori Universitas Trunojoyo Madura | Model *Prototype* (7 tahap: analisis kebutuhan, membangun prototipe, evaluasi prototipe, pengkodean, pengujian, evaluasi sistem, penggunaan sistem) | Sama-sama membangun LMS berbasis web dengan Laravel untuk jenjang sekolah | Penelitian rujukan menggunakan metode *Prototyping*; penelitian ini menggunakan **DDR** dan objek penelitian SMK (bukan SMP), dengan cakupan fitur ujian daring auto-scoring dan RBAC tiga peran |
| 2 | Design and Development of a Learning Management System Information System using the Waterfall Method with the Laravel Framework (studi kasus PT. Kodinglab Integrasi Indonesia) | *Proceedings of International Conference on Islamic Community Studies* (ICIE), Univ. Panca Budi | *Waterfall* | Sama-sama mengembangkan LMS berbasis Laravel | Objek penelitian berupa lembaga pelatihan (non-sekolah formal); metode pengembangan Waterfall, bukan DDR |
| 3 | Design and Development of an E-learning Information System Using the Laravel Framework at SMA Negeri 3 Medan | *Journal of Information Technology, Computer Science and Electrical Engineering* (JITCSE) | Tidak disebutkan eksplisit pada abstrak (perlu verifikasi langsung ke artikel) | Sama-sama mengembangkan sistem e-learning berbasis Laravel untuk sekolah menengah | Objek penelitian SMA (bukan SMK); perlu verifikasi metode pengembangan yang digunakan |
| 4 | Perancangan Sistem Informasi Akademik Sekolah Berbasis Website dengan Laravel 5 pada SMK Negeri 1 Cileles | *Jurnal Ilmiah Sains dan Teknologi*, LPPM Universitas Banten Jaya | *Waterfall* | Sama-sama membangun sistem informasi akademik berbasis Laravel untuk SMK | Fokus penelitian rujukan pada sistem informasi akademik umum (data induk, nilai), belum mencakup modul pembelajaran daring (materi, kuis, ujian auto-scoring, forum diskusi) yang menjadi fokus penelitian ini |
| 5 | Implementasi Framework Laravel pada Sistem Informasi Akademik SMA Negeri 1 Kediri Berbasis Web | *Jurnal Nasional Komputasi dan Teknologi Informasi* (JNKTI) | Perlu verifikasi langsung ke artikel | Sama-sama menerapkan Laravel untuk sistem informasi akademik sekolah | Fokus pada sistem informasi akademik (bukan LMS pembelajaran daring penuh); objek penelitian SMA |

> ⚠️ **TODO (Anda lengkapi/verifikasi):** Tabel di atas disusun dari hasil pencarian web terhadap judul-judul yang benar-benar ada, dengan tautan berikut:
> - [Pengembangan LMS Berbasis Web dengan Framework Laravel di SMP Negeri 1 Bareng — Univ. Trunojoyo](https://library.trunojoyo.ac.id/elib/detil.php?id=33987)
> - [Design and Development of a Learning Management System Information System using the Waterfall Method with the Laravel Framework — Proc. ICIE](https://proceeding.pancabudi.ac.id/index.php/ICIE/article/view/1192)
> - [Design and Development of an E-learning Information System Using the Laravel Framework at SMA Negeri 3 Medan — JITCSE](https://ysmk.org/ejournal/index.php/jitcse/article/view/92)
> - [Perancangan Sistem Informasi Akademik Sekolah Berbasis Website dengan Laravel 5 pada SMK Negeri 1 Cileles — Jurnal Ilmiah Sains dan Teknologi](https://www.ejournal.lppm-unbaja.ac.id/index.php/saintek/article/view/443)
> - [Implementasi Framework Laravel Pada Sistem Informasi Akademik SMA Negeri 1 Kediri Berbasis Web — JNKTI](https://ojs.serambimekkah.ac.id/jnkti/article/view/6090)
>
> **Wajib Anda lakukan sebelum sidang:** buka setiap tautan, catat nama lengkap penulis, tahun terbit, volume/nomor/halaman jurnal (format sitasi lengkap sesuai pedoman kampus — APA/IEEE/dsb.), lalu masukkan ke `DAFTAR_PUSTAKA.md`. Untuk baris 3 dan 5, abstrak hasil pencarian tidak menyebutkan metode pengembangan secara eksplisit — baca isi artikel untuk mengisi kolom "Metode" dengan akurat. Boleh juga ditambah/diganti dengan referensi lain yang lebih relevan dan lebih baru sesuai arahan dosen pembimbing.

## 2.3 Kerangka Berpikir

Kerangka berpikir penelitian ini menggambarkan alur logis dari permasalahan hingga solusi yang dihasilkan, mengikuti tahapan DDR yang dijelaskan pada subbab 2.1.10:

```
[Masalah]
Proses akademik manual (distribusi materi, presensi, tugas, ujian, rekap nilai)
di SMKN 9 Garut belum terintegrasi dalam satu sistem digital
        │
        ▼
[Tahap Analysis]
Identifikasi kebutuhan fungsional (F-A01..F-S08) & nonfungsional (NF-01..NF-07)
melalui observasi proses bisnis dan studi dokumen
        │
        ▼
[Tahap Design]
Perancangan ERD (30 tabel), Use Case Diagram, Activity Diagram,
Sequence Diagram, dan arsitektur MVC berbasis Laravel
        │
        ▼
[Tahap Development]
Implementasi menjadi sistem berjalan: 49 controller, 28 model,
32 migrasi basis data, RBAC 3 peran, integrasi PDF/Excel export
        │
        ▼
[Tahap Evaluation]
Black-box testing (verifikasi kesesuaian fungsional) +
User Acceptance Testing (kuesioner Skala Likert ke Admin/Guru/Siswa)
        │
        ▼
[Hasil]
Sistem LMS SMKN 9 Garut yang teruji secara fungsional dan
diketahui tingkat penerimaannya oleh pengguna nyata
```
