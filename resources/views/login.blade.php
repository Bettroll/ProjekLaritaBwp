@extends('master')

@section('konten_utama')
<div class="card auth-card p-4">
    <div class="card-body">
        <h4 class="fw-bold mb-4" style="color: #8B4513;">Login Member</h4>

        @if(session('error'))
            <div class="pesan-error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="pesan-sukses">{{ session('success') }}</div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-larita btn-lg">Masuk</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="small">Belum punya akun? <a href="/register" style="color: #8B4513; font-weight: bold; text-decoration: none;">Daftar Sekarang</a></p>
        </div>
    </div>
</div>
@endsection