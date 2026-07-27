@extends('layouts.app')
@section('title', 'Backup & Restore')
@section('page-title', 'Backup & Restore Database')
@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card fade-in mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Backup</h5>
                    @if($backupEnabled)
                    <form method="POST" action="{{ route('admin.backup.create') }}">
                        @csrf
                        <button class="btn btn-brand btn-sm"><i class="fa-solid fa-database me-1"></i>Buat Backup Baru</button>
                    </form>
                    @endif
                </div>
                @if(!$backupEnabled)
                <div class="alert alert-info mb-0">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Fitur backup tidak tersedia</strong> di hosting ini karena tidak mendukung akses shell. 
                    Untuk backup database, silakan gunakan <strong>phpMyAdmin</strong> atau fitur backup dari control panel hosting Anda.
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>Nama File</th><th>Ukuran</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            @forelse($files as $file)
                                <tr>
                                    <td>{{ $file['name'] }}</td>
                                    <td>{{ number_format($file['size'] / 1024, 1) }} KB</td>
                                    <td>{{ \Carbon\Carbon::createFromTimestamp($file['modified'])->format('d M Y H:i') }}</td>
                                    <td class="text-end table-actions">
                                        <a href="{{ route('admin.backup.download', $file['name']) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-download"></i></a>
                                        <form action="{{ route('admin.backup.destroy', $file['name']) }}" method="POST" class="d-inline" data-confirm-delete="Hapus file backup ini?">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="empty-state"><i class="fa-solid fa-database"></i><p>Belum ada file backup.</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card fade-in">
            <div class="card-body">
                <h5 class="mb-3"><i class="fa-solid fa-upload me-2 text-danger"></i>Restore Database</h5>
                @if(!$backupEnabled)
                <div class="alert alert-info mb-0">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Fitur restore tidak tersedia</strong> di hosting ini. 
                    Untuk restore database, silakan gunakan <strong>phpMyAdmin</strong> atau fitur import dari control panel hosting Anda.
                </div>
                @else
                <div class="alert alert-warning small">Restore akan menimpa seluruh data pada database saat ini. Pastikan Anda sudah membuat backup terbaru sebelum melanjutkan.</div>
                <form method="POST" action="{{ route('admin.backup.restore') }}" enctype="multipart/form-data" data-confirm-delete="Restore akan menimpa seluruh data saat ini. Lanjutkan?">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">File Backup (.sql)</label>
                        <input type="file" name="backup_file" class="form-control @error('backup_file') is-invalid @enderror" accept=".sql">
                        @error('backup_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-outline-danger w-100"><i class="fa-solid fa-triangle-exclamation me-1"></i>Restore Database</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
