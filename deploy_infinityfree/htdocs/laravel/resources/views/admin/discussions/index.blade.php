@extends('layouts.app')
@section('title', 'Forum Diskusi')
@section('page-title', 'Manajemen Forum Diskusi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form class="d-flex gap-2 mb-3" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari thread" style="width:220px;">
            <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Judul</th><th>Mapel</th><th>Dibuat Oleh</th><th>Komentar</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($discussions as $discussion)
                        <tr>
                            <td>{{ $discussion->title }}</td>
                            <td>{{ $discussion->course->subject->name }}</td>
                            <td>{{ $discussion->user->name }}</td>
                            <td>{{ $discussion->all_comments_count }}</td>
                            <td class="text-end table-actions">
                                <a href="{{ route('admin.discussions.show', $discussion) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye"></i></a>
                                <form action="{{ route('admin.discussions.destroy', $discussion) }}" method="POST" class="d-inline" data-confirm-delete="Hapus thread ini beserta seluruh komentarnya?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-comments"></i><p>Belum ada thread diskusi.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $discussions->links() }}
    </div>
</div>
@endsection
