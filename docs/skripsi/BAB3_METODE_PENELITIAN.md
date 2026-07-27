# BAB III METODE PENELITIAN

## 3.1 Jenis Penelitian

Penelitian ini menggunakan pendekatan **Design and Development Research (DDR) Tipe 1 (*Product and Tool Research*)** sebagaimana dikemukakan oleh Richey & Klein (2007) dan Richey & Nelson (1996), yaitu penelitian yang berfokus pada perancangan, pengembangan, dan evaluasi suatu produk konkret — dalam hal ini sebuah sistem *Learning Management System* (LMS) berbasis web untuk SMKN 9 Garut. Penelitian dilakukan melalui empat tahap sistematis: **Analysis, Design, Development,** dan **Evaluation**, dengan setiap tahap menghasilkan artefak/luaran yang terdokumentasi dan dapat diverifikasi (lihat subbab 3.4).

## 3.2 Tempat dan Waktu Penelitian

**Tempat penelitian:** SMK Negeri 9 Garut.

> ⚠️ **TODO (Anda lengkapi):** Isi alamat lengkap sekolah (jalan, kecamatan, kabupaten Garut, Jawa Barat) sesuai data resmi.

**Waktu penelitian:**

> ⚠️ **TODO (Anda lengkapi):** Isi rentang waktu penelitian sesuai jadwal bimbingan yang disetujui dosen pembimbing, misalnya dalam bentuk tabel jadwal kegiatan berikut (contoh kerangka, angka bulan silakan disesuaikan):

| No | Kegiatan | Bulan ke-1 | Bulan ke-2 | Bulan ke-3 | Bulan ke-4 | Bulan ke-5 |
|---|---|:---:|:---:|:---:|:---:|:---:|
| 1 | Observasi & Analisis Kebutuhan | ✓ | | | | |
| 2 | Perancangan Sistem (ERD, UML) | ✓ | ✓ | | | |
| 3 | Pengembangan/Coding | | ✓ | ✓ | ✓ | |
| 4 | Pengujian Black-Box | | | | ✓ | |
| 5 | Evaluasi Pengguna (UAT) | | | | ✓ | ✓ |
| 6 | Penyusunan Laporan Skripsi | ✓ | ✓ | ✓ | ✓ | ✓ |

## 3.3 Subjek dan Objek Penelitian

**Objek penelitian** adalah sistem *Learning Management System* (LMS) berbasis web yang dikembangkan menggunakan framework Laravel 12, mencakup seluruh modul yang melayani tiga peran pengguna.

**Subjek penelitian** (responden pada tahap evaluasi) terdiri atas tiga kelompok pengguna di lingkungan SMKN 9 Garut:

1. **Administrator** — bertanggung jawab atas pengelolaan data master dan laporan akademik.
2. **Guru** — bertanggung jawab atas pengelolaan materi, tugas, kuis, ujian, presensi, dan nilai.
3. **Siswa** — sebagai pengguna akhir yang mengakses materi, mengerjakan tugas/kuis/ujian, dan melihat hasil belajarnya.

> ⚠️ **TODO (Anda lengkapi):** Tentukan jumlah responden UAT secara pasti, misalnya "1 orang Administrator, 5 orang Guru, dan 20 orang Siswa yang dipilih secara *purposive sampling*" — sesuaikan dengan jumlah pengguna riil yang bersedia menjadi responden dan arahan dosen pembimbing terkait teknik sampling yang sah secara metodologis.

## 3.4 Prosedur Penelitian

Prosedur penelitian mengikuti empat tahap DDR yang dioperasionalkan sebagai berikut:

### 3.4.1 Tahap Analysis (Analisis)

Tahap ini bertujuan mengidentifikasi kebutuhan fungsional dan nonfungsional sistem melalui:

- **Observasi** terhadap proses bisnis akademik yang berjalan di SMKN 9 Garut (distribusi materi, presensi, penugasan, ujian, rekap nilai).
- **Studi dokumen** terhadap struktur organisasi sekolah, mata pelajaran, jurusan/kompetensi keahlian, dan tahun ajaran yang berlaku.
- Perumusan kebutuhan ke dalam daftar kebutuhan fungsional berkode (F-A01–F-A10 untuk Administrator, F-G01–F-G09 untuk Guru, F-S01–F-S08 untuk Siswa) dan kebutuhan nonfungsional (NF-01–NF-07 mencakup *usability, security, performance, reliability, maintainability, portability,* dan *auditability*).

Luaran tahap ini adalah dokumen analisis kebutuhan sistem (disajikan lengkap pada subbab 4.1).

### 3.4.2 Tahap Design (Perancangan)

Tahap ini menerjemahkan kebutuhan menjadi rancangan teknis, meliputi:

- **Perancangan basis data** dalam bentuk Entity Relationship Diagram (ERD), mencakup identifikasi entitas, atribut, relasi antarentitas, dan aturan integritas referensial (`CASCADE`, `RESTRICT`, `SET NULL` pada setiap *foreign key*, disesuaikan dengan makna bisnisnya).
- **Perancangan proses** dalam bentuk Use Case Diagram (memetakan interaksi aktor dengan fungsi sistem), Activity Diagram (memetakan alur kerja tiap fitur utama), dan Sequence Diagram (memetakan interaksi antarkomponen sistem berdasarkan urutan waktu).
- **Perancangan struktur objek** dalam bentuk Class Diagram, memetakan kelas (Model Eloquent), atribut, method bisnis, dan relasi antarkelas sebagai jembatan antara ERD (struktur data) dan implementasi kode (struktur objek).
- **Perancangan arsitektur aplikasi** mengikuti pola *Model-View-Controller* (MVC) bawaan Laravel, dengan pemisahan direktori berdasarkan peran pengguna (`Admin/`, `Guru/`, `Siswa/`) pada lapisan *Controller* dan *View*.

Luaran tahap ini adalah ERD, diagram UML, dan rancangan arsitektur sistem (disajikan lengkap pada subbab 4.2).

### 3.4.3 Tahap Development (Pengembangan)

Tahap ini mengimplementasikan rancangan menjadi kode program yang dapat dieksekusi, meliputi:

- Pembuatan skema basis data melalui *migration* Laravel (32 berkas migrasi menghasilkan 30 tabel).
- Implementasi lapisan *Model* (Eloquent ORM) untuk merepresentasikan entitas data beserta relasinya.
- Implementasi lapisan *Controller* untuk logika bisnis per modul, dipisah menurut peran pengguna.
- Implementasi lapisan *View* menggunakan Blade templating engine dengan kerangka tampilan Bootstrap 5.
- Implementasi mekanisme keamanan: autentikasi (Laravel Fortify), otorisasi berbasis peran (Spatie Laravel-Permission), proteksi CSRF/XSS/SQL Injection, dan *rate limiting* login.
- Integrasi fitur pendukung: ekspor laporan PDF (barryvdh/laravel-dompdf) dan Excel (maatwebsite/excel), notifikasi basis data, dan pencatatan log aktivitas (*activity log*).

Luaran tahap ini adalah sistem LMS yang dapat dijalankan dan diakses melalui peramban web (disajikan lengkap pada subbab 4.3).

### 3.4.4 Tahap Evaluation (Evaluasi)

Tahap ini menilai kelayakan sistem yang telah dibangun melalui dua jenis pengujian:

1. **Black-box testing** — pengujian fungsional terhadap fitur-fitur utama sistem tanpa meninjau struktur kode internal, dilakukan dengan menjalankan skenario uji langsung terhadap sistem yang berjalan (baik secara otomatis melalui simulasi permintaan HTTP maupun secara manual melalui antarmuka peramban) dan membandingkan hasil aktual dengan hasil yang diharapkan.
2. **User Acceptance Testing (UAT)** — evaluasi penerimaan pengguna melalui kuesioner Skala Likert yang disebarkan kepada responden pada subbab 3.3, untuk mengetahui persepsi Administrator, Guru, dan Siswa terhadap kemudahan penggunaan (*usability*), kesesuaian fungsi, dan kepuasan terhadap sistem.

Luaran tahap ini adalah tabel hasil pengujian black-box dan hasil pengolahan kuesioner UAT (disajikan lengkap pada subbab 4.4).

## 3.5 Teknik Pengumpulan Data

| Teknik | Tujuan | Digunakan pada Tahap |
|---|---|---|
| Observasi | Mengamati langsung proses akademik yang berjalan di SMKN 9 Garut | Analysis |
| Wawancara | Menggali kebutuhan spesifik dari calon pengguna (Administrator/Guru) | Analysis |
| Studi Pustaka | Mengkaji teori, metode, dan penelitian terdahulu yang relevan (Bab II) | Analysis, Design |
| Studi Dokumentasi | Menelaah data struktural sekolah (jurusan, mata pelajaran, tahun ajaran) | Analysis, Design |
| Pengujian (Testing) | Memverifikasi kesesuaian fungsional sistem terhadap spesifikasi | Evaluation |
| Kuesioner | Mengukur tingkat penerimaan pengguna terhadap sistem | Evaluation |

## 3.6 Instrumen Penelitian

### 3.6.1 Instrumen Pengujian Black-Box

Instrumen berupa tabel kasus uji (*test case*) dengan struktur kolom: **ID Uji, Skenario Pengujian, Data/Aksi Uji, Hasil yang Diharapkan, Hasil Aktual, Kesimpulan (Valid/Tidak Valid), Metode Verifikasi**. Kasus uji disusun berdasarkan setiap kebutuhan fungsional (F-A01–F-S08) yang telah dirumuskan pada tahap analisis, sehingga setiap kebutuhan memiliki minimal satu kasus uji pembuktian. Instrumen lengkap dan hasil pengujian disajikan pada subbab 4.4.1 dan Lampiran.

### 3.6.2 Instrumen Kuesioner User Acceptance Testing

Instrumen kuesioner disusun menggunakan Skala Likert 5 poin (1 = Sangat Tidak Setuju, 2 = Tidak Setuju, 3 = Cukup/Netral, 4 = Setuju, 5 = Sangat Setuju), dengan kisi-kisi pertanyaan mengacu pada aspek kebutuhan nonfungsional sistem (NF-01–NF-07 pada subbab 4.1.2), yaitu: kemudahan penggunaan (*usability*), keamanan (*security*), performa (*performance*), keandalan (*reliability*), dan kesesuaian fungsi terhadap kebutuhan peran masing-masing responden. Kisi-kisi dan daftar pertanyaan lengkap per peran (Administrator, Guru, Siswa) disajikan pada `LAMPIRAN.md`.

## 3.7 Teknik Analisis Data

### 3.7.1 Analisis Data Pengujian Black-Box

Setiap kasus uji dinyatakan **"Valid"** apabila hasil aktual sistem sesuai dengan hasil yang diharapkan, dan **"Tidak Valid"** apabila terdapat penyimpangan (termasuk *error*, hasil tidak sesuai, atau perilaku tak terduga). Tingkat keberhasilan pengujian dihitung dengan rumus:

```
Persentase Keberhasilan = (Jumlah Kasus Uji Valid / Total Kasus Uji) × 100%
```

Sistem dinyatakan layak dari sisi fungsional apabila seluruh atau sebagian besar (disarankan ≥ 90%) kasus uji dinyatakan valid; kasus uji yang tidak valid ditindaklanjuti dengan perbaikan kode sebelum pengujian diulang (*regression testing*).

### 3.7.2 Analisis Data Kuesioner UAT

Data kuesioner Skala Likert diolah menggunakan teknik **perhitungan persentase kelayakan** (Sugiyono, 2019), dengan rumus:

```
Persentase Kelayakan (%) = (Total Skor yang Diperoleh / Skor Maksimal Ideal) × 100%
```

di mana **Skor Maksimal Ideal** = (Skor tertinggi Skala Likert) × (Jumlah butir pertanyaan) × (Jumlah responden). Hasil persentase kemudian diinterpretasikan menggunakan kategori berikut:

| Rentang Persentase | Kategori Kelayakan |
|---|---|
| 81% – 100% | Sangat Layak |
| 61% – 80% | Layak |
| 41% – 60% | Cukup Layak |
| 21% – 40% | Kurang Layak |
| 0% – 20% | Sangat Tidak Layak |

> ⚠️ **TODO (Anda lengkapi):** Tabel interpretasi di atas adalah rentang umum yang lazim dipakai pada penelitian R&D berskala Likert 5 poin (Sugiyono, 2019). Sesuaikan dengan tabel interpretasi yang diwajibkan dosen pembimbing/program studi jika berbeda.
