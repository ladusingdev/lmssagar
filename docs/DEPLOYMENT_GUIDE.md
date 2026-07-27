# LMS SMKN 9 Garut — Dokumentasi Lengkap

## Overview

**LMS (Learning Management System)** untuk SMKN 9 Garut, dibangun dengan **Laravel 12**. Sistem ini menyediakan fitur manajemen sekolah meliputi: admin, guru, siswa dengan fitur lengkap (materi, tugas, kuis, ujian, absensi, nilai, jadwal, pengumuman, diskusi).

### Info Penting

| Item | Value |
|------|-------|
| **Repo GitHub** | https://github.com/ladusingdev/lmssagar.git |
| **GitHub Account** | ladusing@smknegeri9garut.sch.id |
| **Domain InfinityFree** | https://lmssagar.ct.ws |
| **Railway (cadangan)** | https://lmssagar.up.railway.app |

---

## 1. Setup di Komputer Baru

### Prerequisites

- **PHP 8.2+** (XAMPP / Laragon / Docker)
- **MySQL 8.0+** atau MariaDB 10.6+
- **Composer** (latest)
- **Node.js 18+** (untuk Vite build)
- **Git**
- **WinSCP / FileZilla** (untuk deploy ke InfinityFree)

### Clone & Install

```bash
# Clone repo
git clone https://github.com/ladusingdev/lmssagar.git
cd lmssagar

# Install PHP dependencies
composer install

# Install Node dependencies (untuk build assets)
npm install

# Buat .env
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

### Konfigurasi .env (Local)

```env
APP_NAME="LMS SMKN 9 Garut"
APP_ENV=local
APP_KEY=base64:...                       # otomatis dari key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_smkn9garut               # buat di phpMyAdmin
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### Database Setup

```bash
# Buat database di phpMyAdmin, lalu:
php artisan migrate
php artisan db:seed
```

### Jalankan Server

```bash
php artisan serve
```

Buka http://localhost:8000

### Login Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@smkn9garut.sch.id | password |

---

## 2. Deploy ke InfinityFree (FTP)

### Struktur Folder di Server

```
htdocs/                          <- document root
├── index.php                    <- front controller (arahkan ke laravel/)
├── .htaccess                    <- rewrite rules
├── robots.txt
├── favicon.ico
├── css/, js/, vendor/           <- frontend assets
├── uploads/                     <- pengganti storage:link
│   ├── assignments/
│   ├── avatars/
│   ├── materials/
│   └── submissions/
├── _migrate.php                 <- HAPUS SETELAH DIPAKAI!
└── laravel/                     <- inti Laravel
    ├── .env                     <- KONFIGURASI PRODUCTION
    ├── .htaccess                <- deny all (security)
    ├── app/, config/, routes/
    ├── bootstrap/cache/
    ├── storage/
    └── vendor/                  <- composer dependencies
```

### Upload via WinSCP / FileZilla

1. **Sambung FTP:**
   - Host: `ftpupload.net`
   - User: `if0_42505714`
   - Password: (lihat panel InfinityFree)
   - Port: 21

2. **Upload isi `deploy_infinityfree/htdocs/`** ke folder `htdocs/` di server

3. **Tips upload cepat (FileZilla):**
   - Settings > Transfers > Max simultaneous transfers = **10**
   - Binary mode ON
   - Upload folder `vendor/` terakhir (bisa upload malam hari)

### Post-Upload

1. **Set Permission** via File Manager panel InfinityFree:
   - `htdocs/uploads/` → 777
   - `htdocs/laravel/storage/` → 777 (recursive)
   - `htdocs/laravel/bootstrap/cache/` → 777

2. **Isi .env di server** dengan data panel InfinityFree

3. **Migrasi:** buka di browser
   ```
   https://lmssagar.ct.ws/_migrate.php?token=...&action=migrate
   ```

4. **Seeder:**
   ```
   https://lmssagar.ct.ws/_migrate.php?token=...&action=db:seed
   ```

5. **HAPUS `_migrate.php`** dari server!

### PHP Autoloader Patch

InfinityFree tidak support file > ~1.2MB. File `vendor/composer/autoload_static.php` (1.3MB) tidak bisa di-upload. Solusi: file `autoload_real.php` sudah di-patch untuk load dari `autoload_classmap.php` + `autoload_psr4.php` + `autoload_files.php` langsung (tanpa autoload_static.php).

**Jangan jalankan `composer install/update` di server** — ini akan regenerate autoload_static.php.

---

## 3. Deploy ke Railway (Cadangan)

### Setup

1. Buka https://railway.app → New Project → Deploy from GitHub repo
2. Pilih `ladusingdev/lmssagar`
3. Set Environment Variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:kMvPWglAFIACkEKRhxJV2eDt40LNisBixUXaw6C0qiU=
APP_URL=https://lmssagar.up.railway.app
DB_CONNECTION=pgsql
DB_HOST=db.muvbchegtarmuceqlfcf.supabase.co
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.muvbchegtarmuceqlfcf
DB_PASSWORD=ladusing1!!!
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

4. Enable Public Networking di tab Networking

### ⚠️ Known Issue: IPv6

Railway tidak support IPv6. Supabase pooler (port 6543) digunakan sebagai workaround.

### Run Migration via Railway Shell

Tab Deployments → View Logs → atau tab Shell:
```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## 4. Workflow Git

### Branch Structure

```
main          <- production-ready, deploy ke Railway otomatis
```

### Commit Convention

```
feat: tambah fitur baru
fix: perbaiki bug
chore: maintenance (exclude folder, update config)
docs: dokumentasi
```

### Push ke GitHub

```bash
git add -A
git commit -m "your message"
git push origin main
```

### Sync ke InfinityFree

Tidak ada auto-sync. Upload manual via WinSCP/FileZilla setiap ada perubahan:

```bash
# Hanya upload file yang berubah
# Atau upload seluruh folder htdocs/ dari deploy_infinityfree/
```

**Cara cepat sync perubahan kecil:**
1. Edit file di komputer
2. Upload file yang berubah via WinSCP ke path yang sama di server
3. Contoh: edit `app/Http/Controllers/Admin/DashboardController.php`
   → upload ke `htdocs/laravel/app/Http/Controllers/Admin/DashboardController.php`

---

## 5. Config Files

### .env Production (InfinityFree)

```env
APP_NAME="LMS SMKN 9 Garut"
APP_ENV=production
APP_KEY=base64:Q5yiOUKWyJ+JeVhe6Aiw73rQMX9xFNw5ZqM0r8ZPAvI=
APP_DEBUG=false
APP_URL=https://lmssagar.ct.ws
DB_CONNECTION=mysql
DB_HOST=sql208.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_42505714_lmssagar
DB_USERNAME=if0_42505714
DB_PASSWORD=ladusing1
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
BACKUP_ENABLED=false
```

### FTP Credentials

| Item | Value |
|------|-------|
| Host | `ftpupload.net` |
| User | `if0_42505714` |
| Port | 21 |

### Supabase (untuk Railway)

| Item | Value |
|------|-------|
| Host | `db.muvbchegtarmuceqlfcf.supabase.co` |
| Pooler Port | 6543 |
| DB Name | `postgres` |
| Username | `postgres.muvbchegtarmuceqlfcf` |
| Password | `ladusing1!!!` |

---

## 6. Troubleshooting

### Error 500 di InfinityFree
1. Cek `.env` — pastikan DB_DATABASE benar (`if0_42505714_lmssagar`)
2. Cek `vendor/composer/autoload_real.php` — harus versi patched
3. Cek folder permission: `storage/`, `bootstrap/cache/`, `uploads/`

### "autoload_static.php not found"
File ini > 1.2MB, InfinityFree silently drop. `autoload_real.php` sudah di-patch untuk tidak pakai file ini.

### Tidak bisa upload file besar (>1MB)
InfinityFree limit file per-upload. Gunakan WinSCP (bukan web file manager).

### Session expired terus
Pastikan `htdocs/laravel/storage/framework/sessions/` writable (777).

---

## 7. Fitur LMS

### Admin
- Dashboard (statistik, grafik)
- Manajemen Guru, Siswa, Kelas, Jurusan
- Manajemen Mata Pelajaran & Kurikulum
- Manajemen Jadwal
- Manajemen Materi, Tugas, Kuis, Ujian
- Penilaian & Rapor
- Absensi
- Pengumuman & Diskusi
- Laporan & Export (PDF, Excel)
- Activity Log & Backup

### Guru
- Dashboard guru
- Kelola materi, tugas, kuis, ujian
- Input & review penilaian
- Input absensi
- Lihat jadwal & pengumuman

### Siswa
- Dashboard siswa
- Lihat materi & download
- Submit tugas
- Kerjakan kuis & ujian
- Lihat nilai & absensi
- Lihat jadwal & pengumuman
- Diskusi

---

## 8. Tech Stack

| Component | Technology |
|-----------|-----------|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + Bootstrap 5 + Font Awesome |
| Charts | Chart.js |
| Database | MySQL (InfinityFree) / PostgreSQL (Supabase) |
| Auth | Laravel Fortify + Spatie Permission |
| PDF | DomPDF |
| Excel | Maatwebsite Excel |
| Hosting | InfinityFree (primary) / Railway (backup) |
| VCS | GitHub |

---

## 9. Folder Structure

```
LMS9_/
├── app/
│   ├── Console/Commands/
│   ├── Exports/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   ├── Guru/
│   │   └── Siswa/
│   ├── Models/           (28 models)
│   ├── Notifications/
│   ├── Providers/
│   └── Services/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/       (32 migrations)
│   └── seeders/
├── deploy_infinityfree/  (cadangan deploy, .gitignored)
├── docs/
├── public/
├── resources/views/
│   ├── admin/
│   ├── guru/
│   ├── siswa/
│   └── layouts/
├── routes/
│   ├── admin.php
│   ├── guru.php
│   ├── siswa.php
│   └── web.php
├── stubs/
├── tests/
├── .gitignore
├── .env.example
├── composer.json
├── Procfile
├── railway.toml
└── vite.config.js
```
