@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
    <p class="text-muted small">Masukkan alamat email Anda, kami akan mengirimkan tautan untuk mengatur ulang password.</p>

    @if (session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-brand w-100">
            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Tautan Reset
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i>Kembali ke login</a>
    </div>
@endsection
