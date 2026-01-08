@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Dashboard Ringkasan</h3>
    <p>Selamat datang kembali, {{ Auth::user()->name }}! 👋</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
            <div class="stats-icon"><i class="bi bi-people-fill"></i></div>
            <p class="mb-1">Total Member</p>
            <h2>{{ $count['member'] }}</h2>
            <small>Pengguna terdaftar</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-folder-fill"></i></div>
            <p class="mb-1">Total Kategori</p>
            <h2>{{ $count['kategori'] }}</h2>
            <small>Jenis roti tersedia</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
            <div class="stats-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <p class="mb-1">Total Lokasi</p>
            <h2>{{ $count['lokasi_aktif'] }} / {{ $count['lokasi_total'] }}</h2>
            <small>Cabang aktif</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
            <div class="stats-icon"><i class="bi bi-cart-check-fill"></i></div>
            <p class="mb-1">Total Transaksi</p>
            <h2>{{ $count['transaksi'] }}</h2>
            <small>Pesanan masuk</small>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold" style="color: #8B4513;">
                    <i class="bi bi-lightning-fill me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="/admin/pesanan" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-cart-check-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                            Kelola Pesanan
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/admin/produk" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-box-seam-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                            Tambah Produk
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/admin/laporan" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-bar-chart-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                            Lihat Laporan
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/admin/stok" class="btn btn-outline-warning w-100 py-3">
                            <i class="bi bi-stack d-block mb-2" style="font-size: 1.5rem;"></i>
                            Atur Stok
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/admin/voucher" class="btn btn-outline-danger w-100 py-3">
                            <i class="bi bi-ticket-perforated-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                            Kelola Voucher
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/admin/lokasi" class="btn btn-outline-secondary w-100 py-3">
                            <i class="bi bi-geo-alt-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                            Kelola Lokasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="m-0 fw-bold" style="color: #8B4513;">
                    <i class="bi bi-info-circle-fill me-2"></i>Informasi Sistem
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="rounded-circle p-2 me-3" style="background: rgba(139, 69, 19, 0.1);">
                        <i class="bi bi-calendar-check text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Tanggal Hari Ini</small>
                        <strong>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</strong>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="rounded-circle p-2 me-3" style="background: rgba(28, 200, 138, 0.1);">
                        <i class="bi bi-clock text-success"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Waktu Server</small>
                        <strong>{{ \Carbon\Carbon::now()->format('H:i') }} WIB</strong>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-3" style="background: rgba(54, 185, 204, 0.1);">
                        <i class="bi bi-person-badge text-info"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Login Sebagai</small>
                        <strong>{{ Auth::user()->email }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection