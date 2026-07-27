# Panduan Instalasi — LMS SMKN 9 Garut

Panduan ini ditulis untuk pengguna **Windows + XAMPP**, termasuk yang belum familier dengan Laravel.

> **Ingin memindahkan/melanjutkan proyek ini di laptop lain?** Langsung lompat ke [Bagian 0 — Memindahkan Proyek ke Laptop Baru](#0-memindahkan-proyek-ke-laptop-baru). Bagian 1–2 di bawah adalah panduan instalasi umum yang berlaku untuk laptop baru maupun laptop yang sudah punya XAMPP.

## 0. Memindahkan Proyek ke Laptop Baru

Bagian ini menjelaskan **persis apa yang harus dibawa** dari laptop lama, apa yang **tidak perlu dibawa** (dan kenapa), serta urutan langkah agar proyek langsung bisa jalan di laptop baru.

> **Aplikasi ini sudah 100% siap migrasi offline.** Seluruh aset frontend (Bootstrap, Font Awesome, Chart.js) sudah di-vendor lokal ke `public/vendor/` (±1.6 MB, lihat 0.1) — tidak lagi memuat apa pun dari CDN/internet. Satu-satunya langkah yang *biasanya* butuh internet adalah `composer install` (mengunduh dependency PHP); itu pun bisa dihindari sepenuhnya dengan menyertakan folder `vendor/` PHP saat memindahkan proyek — lihat [0.8 Mode 100% Tanpa Internet](#08-mode-100-tanpa-internet-zero-koneksi).

### 0.1 File/Folder yang WAJIB Dibawa

Salin (copy) seluruh isi folder proyek **kecuali** yang disebut di 0.2, terutama pastikan folder/file berikut ikut terbawa (sering luput karena tersembunyi atau dianggap "file sistem"):

| Item | Kenapa penting |
|---|---|
| `app/`, `bootstrap/`, `config/`, `routes/`, `resources/`, `stubs/`, `tests/` | Seluruh kode aplikasi |
| `public/` (termasuk `public/vendor/`) | Entry point web + aset frontend Bootstrap/Font Awesome/Chart.js yang **sudah di-vendor lokal** (bukan CDN) — wajib ikut agar tampilan tidak rusak |
| `database/migrations/`, `database/factories/`, `database/seeders/` | Struktur & logika pembuatan data |
| `database/lms_smkn9garut.sql` | **Dump database siap import** berisi data bersih hasil seeder (1 Admin, 10 Guru, 100 Siswa, dst.) — cara tercepat & paling offline-friendly untuk membawa data |
| `composer.json` dan `composer.lock` | Daftar dependency PHP + versi pastinya (lihat catatan 0.4) |
| `.env.example` | Template konfigurasi (JANGAN andalkan `.env` lama, buat baru di laptop tujuan) |
| `.gitattributes`, `.gitignore`, `.editorconfig`, `phpunit.xml`, `artisan` | Konfigurasi proyek & entry point CLI |
| `docs/`, `README.md` | Dokumentasi |

### 0.2 File/Folder yang TIDAK PERLU Dibawa (jika laptop tujuan online)

| Item | Kenapa boleh dilewati |
|---|---|
| `vendor/` (folder dependency PHP di root, hasil `composer install`) | Ukurannya besar (ratusan MB) dan akan **dibuat ulang otomatis** di laptop baru dari `composer.json`/`composer.lock` selama laptop tujuan terkoneksi internet. **Jika laptop tujuan TIDAK ADA internet sama sekali, folder ini justru WAJIB dibawa** — lihat [0.8](#08-mode-100-tanpa-internet-zero-koneksi). |
| `.env` | Berisi konfigurasi **spesifik mesin lama** (path `mysqldump.exe`, `APP_KEY`, kredensial DB lama). Buat `.env` baru di laptop tujuan dari `.env.example` (lihat langkah 0.5). |
| `storage/logs/*.log` | Log lama, tidak relevan. |
| `storage/framework/cache/`, `storage/framework/sessions/`, `storage/framework/views/` (isi di dalamnya, bukan foldernya) | Cache runtime, dibuat ulang otomatis. Folder-nya sendiri (dengan file `.gitignore` di dalamnya) tetap harus ada — cukup pastikan struktur folder `storage/framework/{cache,sessions,views}` tetap ada meski kosong. |
| `node_modules/` (jika ada) | Proyek ini **tidak memakai Node/NPM sama sekali** — Bootstrap/Font Awesome/Chart.js sudah di-vendor lokal di `public/vendor/`, jadi tidak relevan. |
| `bootstrap/cache/*.php` (config.php, routes-v7.php, dll — isinya, bukan foldernya) | Cache hasil `artisan config:cache`/`route:cache` dari mesin lama; harus dibuat ulang di mesin baru agar path absolut cocok. |

### 0.3 Cara Memindahkan File

Pilih salah satu cara berikut:

1. **Copy-paste langsung** (paling sederhana): salin folder proyek via flashdisk/hardisk eksternal/cloud storage (Google Drive, OneDrive) ke `C:\xampp\htdocs\` di laptop baru. Kecualikan folder `vendor/` dan file `.env` agar transfer lebih cepat (lihat 0.2).
2. **Kompres jadi ZIP**: klik kanan folder proyek → *Send to → Compressed (zipped) folder*, pindahkan file ZIP-nya, lalu ekstrak di laptop baru ke `C:\xampp\htdocs\LMS9`.
3. **Git (opsional, direkomendasikan untuk pengembangan jangka panjang)**: jika ingin riwayat perubahan tetap terjaga selama pengembangan berlanjut, inisialisasi repository Git di laptop lama:
   ```bash
   git init
   git add .
   git commit -m "Initial commit - LMS SMKN 9 Garut"
   ```
   Lalu push ke GitHub/GitLab (repo privat disarankan karena berisi kredensial contoh & data akademik), dan `git clone` di laptop baru. File `.gitignore` sudah dikonfigurasi agar `vendor/`, `.env`, dan file cache tidak ikut ter-commit.

### 0.4 Instal Ulang Dependency PHP

Setelah file berada di `C:\xampp\htdocs\LMS9` pada laptop baru, buka terminal di folder tersebut dan jalankan:

```bash
composer install
```

Perintah ini membaca `composer.json` + `composer.lock` dan mengunduh seluruh package (Laravel, Fortify, Spatie Permission, DomPDF, Maatwebsite Excel, dst.) persis sesuai versi yang dipakai sebelumnya, lalu membuat ulang folder `vendor/`.

> Jika composer menampilkan peringatan *"lock file is not up to date"*, itu aman diabaikan — cukup lanjutkan `composer install`. Jika ingin menghilangkan peringatan tersebut sepenuhnya, jalankan `composer update` sekali (memperbarui `composer.lock` agar sinkron), lalu commit ulang `composer.lock` jika memakai Git.

### 0.5 Siapkan `.env` Baru

Jangan membawa `.env` dari laptop lama. Buat baru:

```bash
copy .env.example .env
php artisan key:generate
```

Lalu sesuaikan `DB_*` dan `MYSQLDUMP_PATH`/`MYSQL_PATH` mengikuti lokasi instalasi XAMPP di laptop baru (lihat detail di bagian [2.3](#23-konfigurasi-file-env) di bawah).

### 0.6 Lanjutkan ke Setup Database & Menjalankan Aplikasi

Setelah `composer install` dan `.env` siap, lanjutkan seperti instalasi baru pada umumnya: siapkan database (bagian 2.4), `storage:link` (bagian 2.5), lalu jalankan aplikasi (bagian 2.6). Ringkasnya:

```bash
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

(atau import `database/lms_smkn9garut.sql` via phpMyAdmin sebagai pengganti `migrate --seed`, lihat 2.4 Cara A).

### 0.7 Checklist Verifikasi Proyek Siap Beroperasi

- [ ] `composer install` selesai tanpa error, folder `vendor/` terbentuk
- [ ] File `.env` sudah dibuat dan `APP_KEY` sudah ter-generate (`php artisan key:generate`)
- [ ] Database `lms_smkn9garut` sudah ada isinya (via import SQL atau `migrate --seed`)
- [ ] `php artisan storage:link` sudah dijalankan (folder `public/storage` muncul sebagai symlink)
- [ ] `php artisan serve` (atau Apache XAMPP) bisa diakses tanpa error 500
- [ ] Bisa login dengan salah satu akun contoh (lihat bagian 3 di bawah)
- [ ] Menu upload materi/tugas dan export PDF/Excel berfungsi normal

Jika seluruh poin di atas terpenuhi, proyek sudah sepenuhnya beroperasi di laptop baru dan siap dilanjutkan pengembangannya.

### 0.8 Mode 100% Tanpa Internet (Zero Koneksi)

Gunakan alur ini jika laptop tujuan **benar-benar tidak memiliki akses internet** (mis. lab komputer sekolah yang terisolasi), sehingga `composer install` pun tidak bisa dijalankan di sana.

1. Di laptop lama, salin **seluruh folder proyek APA ADANYA, termasuk folder `vendor/`** (folder dependency PHP di root — jangan dikecualikan seperti pada 0.2). Ukuran totalnya beberapa ratus MB, gunakan flashdisk/hardisk eksternal.
2. Pastikan XAMPP (Apache + MySQL + PHP 8.2+) sudah terpasang di laptop tujuan — installer XAMPP wajib diunduh **sebelumnya** selagi masih ada internet, atau dibawa lewat installer offline.
3. Tempel folder proyek ke `C:\xampp\htdocs\LMS9` di laptop tujuan.
4. **Lewati** langkah `composer install` — karena `vendor/` sudah ikut terbawa, seluruh dependency PHP sudah siap pakai (autoloader Laravel memakai path relatif sehingga aman dipindah ke direktori manapun, asalkan strukturnya tetap `C:\xampp\htdocs\LMS9`).
5. Buat `.env` baru dari `.env.example` seperti biasa (langkah 0.5), lalu jalankan `php artisan key:generate` (perintah ini murni lokal, tidak butuh internet).
6. Import `database/lms_smkn9garut.sql` via phpMyAdmin (Cara A di 2.4) — tidak butuh internet karena phpMyAdmin dan MySQL berjalan lokal di XAMPP.
7. Jalankan `php artisan storage:link` lalu akses aplikasi via Apache XAMPP atau `php artisan serve` — seluruh aset (Bootstrap/Font Awesome/Chart.js) sudah dimuat dari `public/vendor/` secara lokal, jadi halaman akan tampil normal walau laptop benar-benar offline.

> Dengan alur ini, **tidak ada satu pun langkah yang memerlukan koneksi internet** di laptop tujuan. Ini adalah cara paling aman untuk demo/sidang skripsi di tempat tanpa WiFi/jaringan.

---

## 1. Prasyarat

| Kebutuhan | Versi Minimum | Keterangan |
|---|---|---|
| XAMPP | 8.2 (PHP 8.2+) | Sertakan Apache & MySQL/MariaDB |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org) |
| Ekstensi PHP | `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd` | Sudah aktif secara default pada XAMPP |

> Proyek ini **tidak memerlukan Node.js/NPM**, dan **tidak memerlukan koneksi internet saat runtime** — seluruh aset frontend (Bootstrap 5, Font Awesome, Chart.js) sudah di-vendor lokal ke `public/vendor/`, bukan dimuat dari CDN.

## 2. Instalasi Langkah demi Langkah

### 2.1 Salin Proyek

Letakkan seluruh folder proyek ke dalam direktori `htdocs` XAMPP, misalnya:

```
C:\xampp\htdocs\LMS9
```

### 2.2 Install Dependency PHP

Buka terminal (PowerShell/CMD) di folder proyek, lalu jalankan:

```bash
composer install
```

### 2.3 Konfigurasi File `.env`

Salin `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

Lalu buat application key:

```bash
php artisan key:generate
```

Buka file `.env` dan sesuaikan bagian berikut sesuai environment Anda:

```env
APP_NAME="LMS SMKN 9 Garut"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/LMS9/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_smkn9garut
DB_USERNAME=root
DB_PASSWORD=

MYSQLDUMP_PATH=C:/xampp/mysql/bin/mysqldump.exe
MYSQL_PATH=C:/xampp/mysql/bin/mysql.exe
```

**Penjelasan variabel penting:**

| Variabel | Keterangan |
|---|---|
| `APP_URL` | Sesuaikan dengan cara Anda mengakses aplikasi — `http://localhost/LMS9/public` jika lewat Apache XAMPP, atau `http://127.0.0.1:8000` jika memakai `php artisan serve` |
| `DB_DATABASE` | Nama database MySQL yang akan dipakai |
| `DB_USERNAME` / `DB_PASSWORD` | Default XAMPP: user `root` tanpa password |
| `MYSQLDUMP_PATH` / `MYSQL_PATH` | Lokasi executable `mysqldump.exe`/`mysql.exe` bawaan XAMPP, dipakai fitur Backup & Restore Database di panel Admin. Gunakan garis miring `/` (bukan `\`) agar tidak menimbulkan error parsing `.env` |

### 2.4 Menyiapkan Database

Pilih **salah satu** dari dua cara berikut:

**Cara A — Import langsung dump SQL (lebih cepat, sudah berisi data contoh):**

1. Buka phpMyAdmin di `http://localhost/phpmyadmin`.
2. Klik "New" untuk membuat database baru bernama `lms_smkn9garut` (atau biarkan kosong karena file dump sudah menyertakan perintah `CREATE DATABASE IF NOT EXISTS`).
3. Pilih database tersebut, buka tab **Import**, pilih file `database/lms_smkn9garut.sql`, lalu klik **Go**.

**Cara B — Migration + Seeder (data dibuat ulang secara acak oleh Faker):**

1. Buat database kosong bernama `lms_smkn9garut` melalui phpMyAdmin.
2. Jalankan:
   ```bash
   php artisan migrate --seed
   ```

### 2.5 Membuat Symbolic Link Storage

Diperlukan agar file yang diunggah (materi, tugas, avatar, logo sekolah) dapat diakses publik:

```bash
php artisan storage:link
```

### 2.6 Menjalankan Aplikasi

**Opsi 1 — Menggunakan Apache bawaan XAMPP:**

Pastikan Apache & MySQL sudah berjalan di XAMPP Control Panel, lalu akses:

```
http://localhost/LMS9/public
```

**Opsi 2 — Menggunakan server bawaan Laravel (untuk pengujian cepat):**

```bash
php artisan serve
```

Lalu akses `http://127.0.0.1:8000`.

## 3. Cara Login

Gunakan salah satu akun berikut (password default seluruh akun hasil seeder: **`password`**):

| Peran | Email | Password |
|---|---|---|
| Administrator | `admin@smkn9garut.sch.id` | `password` |
| Guru | `guru1@smkn9garut.sch.id` (s.d. `guru10@...`) | `password` |
| Siswa | `siswa1@smkn9garut.sch.id` (s.d. `siswa100@...`) | `password` |

Setelah login, sistem otomatis mengarahkan pengguna ke dashboard sesuai perannya (`/admin/dashboard`, `/guru/dashboard`, atau `/siswa/dashboard`).

> **Penting:** Tidak ada halaman registrasi publik. Akun Guru dan Siswa baru dibuat oleh Administrator melalui menu **Manajemen Guru** / **Manajemen Siswa**, yang otomatis membuat akun `users` sekaligus profil `teachers`/`students` terkait.

## 4. Konfigurasi Tambahan (Opsional)

### 4.1 Email (Reset Password & Verifikasi Email)

Secara default `MAIL_MAILER=log`, artinya email tidak benar-benar terkirim, hanya dicatat di `storage/logs/laravel.log`. Untuk mengirim email sungguhan, ubah pengaturan `MAIL_*` di `.env` sesuai SMTP yang tersedia (mis. Gmail SMTP, Mailtrap untuk testing).

### 4.2 Backup & Restore Database

Fitur ini (menu **Backup & Restore** di panel Admin) memanggil `mysqldump`/`mysql` melalui path yang dikonfigurasi pada `MYSQLDUMP_PATH`/`MYSQL_PATH`. Pastikan path tersebut sesuai lokasi instalasi XAMPP Anda.

## 5. Menjalankan Test Otomatis

```bash
php artisan test
```

## 6. Panduan Deployment (Production)

Jika ingin men-deploy aplikasi ke server produksi (bukan sekadar XAMPP lokal):

1. **Environment**: set `APP_ENV=production` dan `APP_DEBUG=false` di `.env` server produksi.
2. **Optimasi Laravel**:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. **Database**: jalankan `php artisan migrate --force` (jangan gunakan `--seed` di produksi kecuali memang ingin data contoh), atau import `lms_smkn9garut.sql` lalu sesuaikan datanya.
4. **Storage**: jalankan `php artisan storage:link` di server, dan pastikan folder `storage/` serta `bootstrap/cache/` dapat ditulis oleh web server (`chmod -R 775` di Linux).
5. **HTTPS**: aktifkan HTTPS dan set `SESSION_SECURE_COOKIE=true` di `.env` bila situs sudah menggunakan SSL.
6. **Web server root**: arahkan document root web server ke folder `public/`, bukan ke root proyek.
7. **Queue & Scheduler** (opsional, jika notifikasi/email diproses secara asynchronous): jalankan `php artisan queue:work` sebagai service, dan tambahkan `php artisan schedule:run` ke cron job setiap menit.
8. **Backup rutin**: jadwalkan backup database berkala (cron + `mysqldump`) terpisah dari fitur Backup & Restore bawaan aplikasi, sebagai lapisan pengamanan tambahan.

## 7. Troubleshooting Umum

| Masalah | Solusi |
|---|---|
| Halaman blank / error 500 | Cek `storage/logs/laravel.log` untuk detail error. Pastikan `.env` sudah benar dan `php artisan key:generate` sudah dijalankan |
| `SQLSTATE[HY000] [1045] Access denied` | Periksa `DB_USERNAME`/`DB_PASSWORD` di `.env` sesuai kredensial MySQL Anda |
| File upload gagal ditampilkan | Jalankan `php artisan storage:link` dan pastikan folder `storage/app/public` dapat ditulis |
| Fitur Backup Database gagal | Periksa `MYSQLDUMP_PATH`/`MYSQL_PATH` di `.env`, pastikan menggunakan garis miring `/` bukan `\` |
| Halaman CSS/JS tidak tampil rapi | Pastikan folder `public/vendor/` (Bootstrap, Font Awesome, Chart.js) ikut terbawa saat migrasi — aset ini di-vendor lokal, bukan CDN, jadi tidak butuh internet, tapi filenya harus ada. Cek juga `public/css/app.css` dan `public/js/app.js` termuat dengan benar |
