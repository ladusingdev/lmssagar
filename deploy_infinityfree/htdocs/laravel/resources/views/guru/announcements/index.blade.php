@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('guru.announcements.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Buat Pengumuman</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td>{{ $announcement->title }}</td>
                            <td>{{ $announcement->created_at->format('d M Y H:i') }}</td>
                            <td>{!! $announcement->is_published ? '<span class="badge bg-success">Publish</span>' : '<span class="badge bg-warning text-dark">Draft</span>' !!}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('guru.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('guru.announcements.destroy', $announcement) }}" method="POST" class="d-inline" data-confirm-delete="Hapus pengumuman ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state"><i class="fa-solid fa-bullhorn"></i><p>Belum ada pengumuman.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $announcements->links() }}
    </div>
</div>
@endsection
