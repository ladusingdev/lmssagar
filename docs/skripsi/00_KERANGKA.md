# Kerangka Penulisan Skripsi

**Judul:** RANCANG BANGUN LEARNING MANAGEMENT SYSTEM (LMS) BERBASIS WEB MENGGUNAKAN METODE DESIGN AND DEVELOPMENT RESEARCH (DDR) DAN FRAMEWORK LARAVEL DI SMKN 9 GARUT

**Penulis:** Asep Suryana — NIM 24572014006
**Program Studi:** Sistem Informasi — Institut Pendidikan Indonesia

---

## Cara memakai folder ini

Setiap Bab ada di file Markdown terpisah supaya mudah direvisi bertahap. Urutan baca/edit:

1. `BAB1_PENDAHULUAN.md`
2. `BAB2_TINJAUAN_PUSTAKA.md`
3. `BAB3_METODE_PENELITIAN.md`
4. `BAB4_HASIL_PEMBAHASAN.md`
5. `BAB5_PENUTUP.md`
6. `DAFTAR_PUSTAKA.md`
7. `LAMPIRAN.md`

Setelah semua Bab disetujui, salin isinya ke Microsoft Word / Google Docs lalu terapkan format resmi kampus (margin, font, spasi, penomoran halaman, halaman sampul, lembar pengesahan, dll — karena hal ini belum ditentukan saat penulisan ini dibuat).

## ⚠️ Bagian yang WAJIB Anda lengkapi/verifikasi sendiri (bukan karangan AI)

Ditandai dengan blok `> ⚠️ TODO` di setiap file. Ringkasannya:

| Lokasi | Yang perlu dilengkapi | Kenapa tidak diisi otomatis |
|---|---|---|
| Bab I | Tanggal observasi awal, nama guru/staf yang diwawancara (jika ada) | Data primer, harus sesuai kejadian nyata |
| Bab II | Verifikasi ulang setiap sitasi di tabel Penelitian Terdahulu (nama penulis lengkap, tahun, volume/halaman jurnal) | Hasil pencarian web hanya memuat cuplikan; nomor halaman/volume jurnal perlu dicek langsung ke sumber agar sitasi akurat |
| Bab III | Tanggal mulai-selesai penelitian, lokasi tepat (alamat sekolah), nama dosen pembimbing | Data administratif yang hanya Anda/kampus yang tahu |
| Bab IV | Hasil kuesioner UAT (skor dari guru/siswa/admin sungguhan) | Data primer hasil survei — mengarang angka ini adalah pelanggaran integritas akademik |
| Bab V | — | Kesimpulan & saran sudah diturunkan dari hasil Bab IV, tinggal disesuaikan gaya bahasa |
| Lampiran | Tanda tangan responden, surat izin penelitian dari sekolah, dokumentasi wawancara | Dokumen fisik/administratif |

## Metodologi yang dipakai: Design and Development Research (DDR)

Mengacu pada Richey & Klein (2007) — DDR Tipe 1 (*Product and Tool Research*), dengan 4 tahap yang dipetakan langsung ke pekerjaan yang sudah dilakukan di proyek `LMS9`:

| Tahap DDR | Output di proyek nyata |
|---|---|
| **Analysis** | `docs/ANALISIS_KEBUTUHAN.md` — kebutuhan fungsional (F-A01–F-S08) & nonfungsional (NF-01–NF-07) |
| **Design** | `docs/ERD.md` (30 tabel), `docs/DIAGRAM.md` (use case, activity, sequence diagram) |
| **Development** | Kode aplikasi Laravel 12 di `app/`, `database/migrations/`, `resources/views/` — 49 controller, 28 model, 32 migrasi |
| **Evaluation** | Pengujian black-box (dieksekusi nyata terhadap sistem berjalan) + kuesioner UAT (template disediakan, hasil menyusul) |
