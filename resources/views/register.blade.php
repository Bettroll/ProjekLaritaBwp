@extends('master')

@section('konten_utama')
<div class="card auth-card p-4">
    <div class="card-body">
        <h4 class="fw-bold mb-4" style="color: #8B4513;">Registrasi Member</h4>

        <form action="/register" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-larita btn-lg">Daftar Member</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="small">Sudah punya akun? <a href="/login" style="color: #8B4513; font-weight: bold; text-decoration: none;">Kembali Login</a></p>
        </div>
    </div>
</div>
@endsection