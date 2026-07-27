@extends('layouts.app')
@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <form class="d-flex gap-2 flex-wrap mb-3" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari aktivitas" style="width:220px;">
            <select name="action" class="form-select form-select-sm" style="width:180px;">
                <option value="">Semua Aksi</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucfirst($action) }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Deskripsi</th><th>IP Address</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="small">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $log->user->name ?? 'System' }}</td>
                            <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td class="small text-muted">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-clock-rotate-left"></i><p>Belum ada aktivitas tercatat.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
