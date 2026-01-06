@extends('admin_master')

@section('konten_admin')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h3 class="fw-bold">Dashboard Ringkasan</h3>
            <p class="text-secondary">Selamat bekerja kembali, {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <!-- Kotak Statistik (Sesuai Konseping kamu) -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3">
                <h5>Total Member</h5>
                <h2 class="fw-bold">{{ $count['member'] }}</h2>
                <small>Orang terdaftar</small>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-success text-white p-3">
                <h5>Total Kategori</h5>
                <h2 class="fw-bold">{{ $count['kategori'] }}</h2>
                <small>Jenis roti</small>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-info text-white p-3">
                <h5>Total Lokasi</h5>
                <h2>{{ $count['lokasi_aktif'] }} / {{ $count['lokasi_total'] }}</h2>
                <small>Cabang Toko Larita</small>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-warning text-dark p-3">
                <h5>Total Transaksi</h5>
                <h2 class="fw-bold">{{ $count['transaksi'] }}</h2>
                <small>Pesanan masuk</small>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold">Aktivitas Terbaru</h5>
                <p class="text-muted">Fitur ini akan muncul jika transaksi sudah berjalan.</p>
                <hr>
                <p>Silakan gunakan menu di samping untuk mengelola data master Toko Larita.</p>
            </div>
        </div>
    </div>
</div>
@endsection