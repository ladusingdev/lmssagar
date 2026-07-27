# Progress Deploy LMS ke InfinityFree

## Status: UPLOAD SEDANG BERLANGSUNG (manual WinSCP)

---

## Checklist

- [x] Audit `deploy_infinityfree/` package
- [x] Fix `.env` - domain & database sudah dikonfigurasi
- [x] Hapus `upload_worker.ps1` (credential hardcode leak)
- [ ] **Upload isi `deploy_infinityfree/htdocs/` via WinSCP/FileZilla ke `htdocs/` di server**
- [ ] Set permission 755/777 pada folder: `uploads/`, `laravel/storage/`, `laravel/bootstrap/cache/`
- [ ] Jalankan migrasi: `https://lmssagar.ct.ws/_migrate.php?token=614f0ee81481453bf3cc5a82b877c8cb57dc1a6a&action=migrate`
- [ ] Jalankan seeder: `https://lmssagar.ct.ws/_migrate.php?token=614f0ee81481453bf3cc5a82b877c8cb57dc1a6a&action=db:seed`
- [ ] **HAPUS `_migrate.php` dari server**
- [ ] Test login admin: `admin@smkn9garut.sch.id` / `password`
- [ ] Ganti password admin
- [ ] Test login guru & siswa
- [ ] Test upload materi/tugas

---

## Konfigurasi Server

| Item | Value |
|------|-------|
| **Domain** | `lmssagar.ct.ws` |
| **FTP Host** | `ftpupload.net` |
| **FTP User** | `if0_42505714` |
| **FTP Port** | 21 |
| **DB Host** | `sql208.infinityfree.com` |
| **DB Name** | `if0_42505714_XXX` |
| **DB User** | `if0_42505714` |
| **DB Password** | `ladusing1` |
| **Admin Login** | `admin@smkn9garut.sch.id` / `password` |

---

## File yang Sudah Diperbaiki

1. **`.env`** - 5 placeholder sudah diganti dengan data server
2. **`upload_worker.ps1`** - dihapus (credential leak)

---

## Tips Upload Cepat (FileZilla)

1. Edit > Settings > Transfers > **Maximum simultaneous transfers = 10**
2. Binary mode ON
3. Passive mode ON
4. Upload folder `vendor/` terakhir (paling banyak file kecil)
5. Bisa upload malam hari (biarkan jalan sendiri)

---

## Setelah Upload Selesai

### 1. Set Permission
Set permission ke **755** (atau 777 kalau 755 gagal):
```
htdocs/uploads/
htdocs/laravel/storage/
htdocs/laravel/bootstrap/cache/
```

### 2. Jalankan Migrasi
Buka di browser:
```
https://lmssagar.ct.ws/_migrate.php?token=614f0ee81481453bf3cc5a82b877c8cb57dc1a6a&action=migrate
```

### 3. Jalankan Seeder
```
https://lmssagar.ct.ws/_migrate.php?token=614f0ee81481453bf3cc5a82b877c8cb57dc1a6a&action=db:seed
```

### 4. HAPUS _migrate.php dari server (penting!)

---

## Error Handling

Kalau ada error, kirim:
1. Screenshot/pesan error yang muncul
2. Atau buka `.env`, set `APP_DEBUG=true` untuk melihat error detail

---

## Struktur Folder Deploy

```
deploy_infinityfree/htdocs/
├── index.php              <- Front controller
├── .htaccess              <- Rewrite rules
├── robots.txt
├── favicon.ico
├── css/                   <- Frontend assets
├── js/
├── vendor/                <- Bootstrap, Font Awesome, Chart.js
├── uploads/               <- Pengganti storage:link
├── _migrate.php           <- HAPUS setelah dipakai!
└── laravel/               <- Inti Laravel
    ├── .env               <- Konfigurasi
    ├── app/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    └── vendor/            <- Composer dependencies
```

---

## Referensi

- Panduan lengkap: `deploy_infinityfree/README_DEPLOY.md`
- File penting yang tidak boleh dihapus: `index.php`, `.htaccess`, `.env`
