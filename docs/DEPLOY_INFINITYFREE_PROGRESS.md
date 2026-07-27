# Progress Deploy LMS ke InfinityFree

## Status: SELESAI ✅

---

## Checklist

- [x] Audit `deploy_infinityfree/` package
- [x] Fix `.env` - domain & database sudah dikonfigurasi
- [x] Hapus `upload_worker.ps1` (credential hardcode leak)
- [x] Upload isi `deploy_infinityfree/htdocs/` via WinSCP ke `htdocs/`
- [x] Fix autoload_static.php (file > 1.2MB, InfinityFree silent drop)
- [x] Patch autoload_real.php (load dari autoload_classmap + psr4 + files)
- [x] Jalankan migrasi via browser
- [x] Jalankan seeder via browser
- [x] Set APP_DEBUG=false
- [x] Hapus debug files (phpinfo.php, debug.php, dll)
- [x] Audit deployment (11 PASS, 0 FAIL)
- [ ] **HAPUS `_migrate.php` dari server** ← SECURITY: masih ada!
- [x] Login test: admin@smkn9garut.sch.id / password ✅

---

## Konfigurasi Server

| Item | Value |
|------|-------|
| **Domain** | `lmssagar.ct.ws` |
| **FTP Host** | `ftpupload.net` |
| **FTP User** | `if0_42505714` |
| **FTP Port** | 21 |
| **DB Host** | `sql208.infinityfree.com` |
| **DB Name** | `if0_42505714_lmssagar` |
| **DB User** | `if0_42505714` |
| **DB Password** | `ladusing1` |
| **Admin Login** | `admin@smkn9garut.sch.id` / `password` |

---

## Known Issues & Fixes

### autoload_static.php > 1.2MB
InfinityFree silently drops files > ~1.2MB. Fix: `autoload_real.php` di-patch untuk load dari file-file lain tanpa autoload_static.php.

**JANGAN jalankan `composer install/update` di server** — ini akan regenerate autoload_static.php.

---

## Referensi

- Panduan lengkap: `docs/DEPLOYMENT_GUIDE.md`
- Panduan deploy InfinityFree: `deploy_infinityfree/README_DEPLOY.md`
