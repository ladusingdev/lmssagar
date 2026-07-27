@extends('layouts.app')
@section('title', 'Forum Diskusi')
@section('page-title', 'Forum Diskusi')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('siswa.discussions.create') }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Buat Thread</a>
        </div>
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
                                <a href="{{ route('siswa.discussions.show', $discussion) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye"></i></a>
                                @if($discussion->user_id === auth()->id())
                                    <form action="{{ route('siswa.discussions.destroy', $discussion) }}" method="POST" class="d-inline" data-confirm-delete="Hapus thread ini?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
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
