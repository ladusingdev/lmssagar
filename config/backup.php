<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Backup Feature
    |--------------------------------------------------------------------------
    |
    | Enable or disable the database backup/restore feature. On shared hosting
    | (like InfinityFree) where shell_exec is not available, set this to false.
    |
    */

    'enabled' => env('BACKUP_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Database Backup Binaries
    |--------------------------------------------------------------------------
    |
    | Paths to the mysqldump and mysql client executables used by the
    | Backup & Restore Database feature. Defaults target a standard XAMPP
    | installation on Windows. Override via .env if your setup differs.
    |
    */

    'mysqldump_path' => env('MYSQLDUMP_PATH', 'C:\\xampp\\mysql\\bin\\mysqldump.exe'),

    'mysql_path' => env('MYSQL_PATH', 'C:\\xampp\\mysql\\bin\\mysql.exe'),

];
