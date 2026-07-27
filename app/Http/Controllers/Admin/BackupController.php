<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    private const DISK = 'local';

    private const DIRECTORY = 'backups';

    public function index(): View
    {
        $backupEnabled = config('backup.enabled', true);

        $files = collect();
        if ($backupEnabled) {
            $files = collect(Storage::disk(self::DISK)->files(self::DIRECTORY))
                ->filter(fn ($file) => str_ends_with($file, '.sql'))
                ->map(fn ($file) => [
                    'name' => basename($file),
                    'size' => Storage::disk(self::DISK)->size($file),
                    'modified' => Storage::disk(self::DISK)->lastModified($file),
                ])
                ->sortByDesc('modified')
                ->values();
        }

        return view('admin.backup.index', compact('files', 'backupEnabled'));
    }

    public function create(): RedirectResponse
    {
        if (! config('backup.enabled', true)) {
            return back()->with('error', 'Fitur backup tidak tersedia di hosting ini.');
        }

        $config = config('database.connections.mysql');
        $filename = 'backup_'.now()->format('Y_m_d_His').'.sql';
        $path = storage_path('app/'.self::DIRECTORY.'/'.$filename);

        Storage::disk(self::DISK)->makeDirectory(self::DIRECTORY);

        $process = new Process([
            config('backup.mysqldump_path', 'mysqldump'),
            '--host='.$config['host'],
            '--port='.$config['port'],
            '--user='.$config['username'],
            '--password='.$config['password'],
            '--single-transaction',
            '--routines',
            $config['database'],
        ]);

        $process->setTimeout(300);

        try {
            $process->mustRun();
            file_put_contents($path, $process->getOutput());
        } catch (ProcessFailedException $e) {
            return back()->with('error', 'Gagal membuat backup: '.$e->getMessage());
        }

        ActivityLogger::log('backup', "Membuat backup database: {$filename}");

        return back()->with('success', 'Backup database berhasil dibuat.');
    }

    public function download(string $file)
    {
        $path = self::DIRECTORY.'/'.basename($file);
        abort_unless(Storage::disk(self::DISK)->exists($path), 404);

        return Storage::disk(self::DISK)->download($path);
    }

    public function destroy(string $file): RedirectResponse
    {
        $path = self::DIRECTORY.'/'.basename($file);
        Storage::disk(self::DISK)->delete($path);
        ActivityLogger::log('delete', "Menghapus file backup: {$file}");

        return back()->with('success', 'File backup berhasil dihapus.');
    }

    public function restore(Request $request): RedirectResponse
    {
        if (! config('backup.enabled', true)) {
            return back()->with('error', 'Fitur restore tidak tersedia di hosting ini.');
        }

        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:sql', 'max:51200'],
        ]);

        $config = config('database.connections.mysql');
        $uploaded = $request->file('backup_file');
        $tempPath = $uploaded->getRealPath();

        $process = new Process([
            config('backup.mysql_path', 'mysql'),
            '--host='.$config['host'],
            '--port='.$config['port'],
            '--user='.$config['username'],
            '--password='.$config['password'],
            $config['database'],
        ]);

        $process->setInput(fopen($tempPath, 'r'));
        $process->setTimeout(300);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            return back()->with('error', 'Gagal melakukan restore: '.$e->getMessage());
        }

        ActivityLogger::log('restore', 'Melakukan restore database dari file backup');

        return back()->with('success', 'Database berhasil di-restore.');
    }
}
