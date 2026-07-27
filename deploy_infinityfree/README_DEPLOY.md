# Panduan Deploy LMS ke InfinityFree

Folder ini (`deploy_infinityfree/`) adalah **paket siap upload**, terpisah dari project
utama supaya testing lokal Anda di XAMPP tetap jalan normal. Isinya sudah disesuaikan
untuk keterbatasan InfinityFree (tanpa SSH, tanpa Composer/artisan di server, tanpa symlink).

Struktur:
```
deploy_infinityfree/
└── htdocs/                <- ISI FOLDER INI yang di-upload ke document root InfinityFree
    ├── index.php           (sudah diarahkan ke folder laravel/)
    ├── .htaccess
    ├── css/, js/, vendor/, favicon.ico, robots.txt   (asset publik)
    ├── uploads/             <- pengganti "storage:link", tempat file materi/tugas/avatar
    ├── _migrate.php         <- alat migrasi sementara (WAJIB dihapus setelah dipakai)
    └── laravel/             <- seluruh inti Laravel (app, config, vendor, dst)
```

## Langkah-langkah

### 1. Daftar & siapkan akun InfinityFree
1. Daftar di infinityfree.net, buat hosting account baru.
2. Di panel (client area), buat subdomain gratis (mis. `lms-anda.infinityfreeapp.com`) atau
   hubungkan domain sendiri kalau punya.
3. Masuk ke **MySQL Databases**, buat 1 database baru. Catat 4 hal ini (akan muncul di panel):
   - Hostname database (biasanya seperti `sqlXXX.infinityfree.com`)
   - Nama database (format `epiz_xxxxxxxx_namadb`)
   - Username (biasanya sama dengan nama database)
   - Password yang Anda set sendiri

### 2. Lengkapi file `.env`
Buka `deploy_infinityfree/htdocs/laravel/.env`, isi bagian yang masih bertuliskan
`GANTI-DENGAN-...`:
- `APP_URL` -> alamat domain/subdomain Anda (pakai `https://`)
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` -> dari langkah 1

`APP_KEY` sudah digenerate otomatis, tidak perlu diubah.

**Pengaturan penting di `.env`:**
```
APP_ENV=production
APP_DEBUG=false
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
CACHE_STORE=file
BACKUP_ENABLED=false      <- Fitur backup via CLI dinonaktifkan (tidak ada shell di shared hosting)
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### 3. Upload ke server
**Gunakan FTP client (disarankan: FileZilla), jangan file manager berbasis web** — folder
`vendor/` berisi ribuan file kecil, dan file manager web InfinityFree biasanya membatasi
ukuran upload per-file (~8-10MB), sedangkan lewat FTP tidak masalah walau lebih lama.

1. Buka **FTP Details** di panel InfinityFree untuk dapat host/username/password FTP.
2. Sambungkan dengan FileZilla.
3. Upload **seluruh isi** folder `deploy_infinityfree/htdocs/` ke folder `htdocs/` di server
   (folder tujuan biasanya sudah bernama `htdocs` bawaan InfinityFree — upload isinya
   langsung ke situ, jangan bikin subfolder tambahan).
4. Proses upload vendor/ akan makan waktu paling lama (bisa puluhan menit) — biarkan sampai
   selesai, jangan diputus di tengah jalan.

### 4. Beri izin tulis ke folder yang butuh ditulis
Lewat FTP client atau File Manager, set permission (klik kanan > File permissions) ke
**755** (atau 777 kalau 755 masih gagal) untuk folder-folder ini beserta isinya:
- `htdocs/uploads/`
- `htdocs/laravel/storage/` (beserta seluruh sub-foldernya)
- `htdocs/laravel/bootstrap/cache/`

### 5. Jalankan migrasi database (pengganti `php artisan migrate`)
1. Buka di browser:
   `https://domain-anda.com/_migrate.php?token=614f0ee81481453bf3cc5a82b877c8cb57dc1a6a&action=migrate`
2. Kalau berhasil, akan muncul tulisan "Migration OK" / daftar tabel yang dibuat.
3. Kalau Anda juga ingin mengisi data awal (akun admin dsb, kalau ada seeder-nya), ganti
   `action=migrate` jadi `action=db:seed` dan buka lagi.
4. **SEGERA HAPUS `_migrate.php` dari server lewat FTP setelah selesai.** File ini
   berbahaya kalau dibiarkan — siapa pun yang tahu URL & token-nya bisa menjalankan ulang
   migrasi (termasuk `migrate:fresh` yang MENGHAPUS semua data) kapan saja.

### 6. Isi data awal (role/permission + akun admin) lewat seeder
Project ini sudah punya `DatabaseSeeder` yang otomatis membuat:
- Role & permission (wajib ada, tanpa ini semua login akan gagal)
- 1 akun admin: **admin@smkn9garut.sch.id** / password: **password**
- Data contoh (guru, siswa, kelas, materi, tugas, kuis, dll) — bagus untuk keperluan
  demo sidang supaya sistem langsung terlihat "berisi", tidak kosong.

Jalankan dengan membuka:
`https://domain-anda.com/_migrate.php?token=614f0ee81481453bf3cc5a82b877c8cb57dc1a6a&action=db:seed`

**Penting:** setelah login pertama kali sebagai admin, segera ganti password default
`password` itu lewat menu profil.

### 7. Uji coba
Buka domain Anda di browser, login, dan coba alur utama: login guru, upload materi,
lihat sebagai siswa, cek notifikasi — persis seperti yang sudah Anda tes di lokal.

## Keterbatasan yang perlu Anda tahu (bukan bug, memang batasan InfinityFree)

- **Fitur Backup Database** (menu Admin > Backup) sudah dinonaktifkan di production karena
  memakai perintah `mysqldump` lewat shell — hosting gratis seperti InfinityFree menonaktifkan
  eksekusi perintah shell demi keamanan. Gunakan **phpMyAdmin > Export** di panel InfinityFree
  sebagai gantinya untuk backup manual.
- **Kirim email** (reset password, dsb) kemungkinan tidak terkirim karena banyak hosting
  gratis memblokir koneksi SMTP keluar. Untuk sidang/demo ini biasanya tidak masalah.
- Tidak ada cron/scheduler — tidak masalah untuk aplikasi ini karena tidak ada fitur yang
  bergantung padanya saat ini.
- **Session & Cache** menggunakan driver `file` (bukan database) untuk menghemat resource.

## Kalau ada error setelah online
Kirim ke saya pesan error persis yang muncul di layar (atau screenshot) — saya bisa
telusuri penyebabnya dari sini, perbaiki di paket `deploy_infinityfree/` ini, lalu Anda
tinggal re-upload file yang berubah saja.

## Checklist Deployment
- [ ] Buat akun InfinityFree + hosting account
- [ ] Buat database di MySQL Databases
- [ ] Isi `.env` dengan data database dari cPanel
- [ ] Set `APP_URL` ke domain/subdomain yang benar
- [ ] Upload seluruh isi `htdocs/` via FileZilla
- [ ] Set permission 755/777 pada `uploads/`, `storage/`, `bootstrap/cache/`
- [ ] Buka `_migrate.php` di browser untuk migrasi
- [ ] Jalankan `db:seed` untuk data awal
- [ ] **HAPUS `_migrate.php` dari server**
- [ ] Login sebagai admin (admin@smkn9garut.sch.id / password)
- [ ] Ganti password admin
- [ ] Test login guru & siswa
- [ ] Test upload materi/tugas
