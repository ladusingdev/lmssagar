<?php

/**
 * PENTING - BACA SEBELUM PAKAI:
 * File ini adalah pengganti sementara "php artisan migrate" karena InfinityFree
 * tidak menyediakan akses SSH/terminal. Cara pakai:
 *
 *   1. Upload file ini ke folder htdocs/ (sejajar dengan index.php).
 *   2. Buka di browser: https://domain-anda.com/_migrate.php?token=SECRET_DI_BAWAH&action=migrate
 *   3. Setelah berhasil dan tampil "Migration OK", SEGERA HAPUS file ini dari server.
 *      Jangan biarkan file ini tetap ada — siapa pun yang tahu URL & token bisa
 *      menjalankan ulang migrasi/seed kapan saja.
 */

define('MIGRATE_SECRET_TOKEN', '614f0ee81481453bf3cc5a82b877c8cb57dc1a6a');

$token = $_GET['token'] ?? '';

if (! hash_equals(MIGRATE_SECRET_TOKEN, $token)) {
    http_response_code(404);
    exit('Not found.');
}

require __DIR__.'/laravel/vendor/autoload.php';

/** @var Illuminate\Foundation\Application $app */
$app = require __DIR__.'/laravel/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

$action = $_GET['action'] ?? 'migrate';

try {
    switch ($action) {
        case 'migrate':
            $exitCode = Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            break;
        case 'migrate:fresh':
            // Hapus SEMUA tabel lalu migrate ulang dari kosong. Hati-hati, ini menghapus data!
            $exitCode = Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
            break;
        case 'db:seed':
            $exitCode = Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            break;
        default:
            exit('Action tidak dikenal. Gunakan ?action=migrate, migrate:fresh, atau db:seed.');
    }

    echo Illuminate\Support\Facades\Artisan::output();
    echo "\n\nSelesai. Exit code: {$exitCode}\n";
    echo "\n>>> SEKARANG HAPUS FILE _migrate.php INI DARI SERVER. <<<\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: '.$e->getMessage();
}
