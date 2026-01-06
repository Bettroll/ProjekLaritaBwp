<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Larita Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { height: 100vh; background: #343a40; color: white; padding-top: 20px; }
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background: #495057; color: white; }
        .sidebar .active { background: #8B4513; color: white; }
        .content { padding: 20px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <h4 class="text-center fw-bold text-warning mb-4">LARITA ADMIN</h4>
            <a href="/admin/dashboard">🏠 Dashboard</a>
            <hr>
            <p class="px-3 small text-uppercase text-secondary">Master Data</p>
            <a href="/admin/kategori">📂 Master Kategori</a>
            <a href="/admin/lokasi">📍 Master Lokasi</a>
            <a href="/admin/produk">🍞 Master Produk Roti</a>
            <hr>
            <p class="px-3 small text-uppercase text-secondary">Transaksi</p>
            <a href="/admin/transaksi">🛒 Pesanan Masuk</a>
            <a href="/admin/laporan">📊 Laporan Penjualan</a>
            <hr>
            <form action="/logout" method="POST" class="px-3">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">Keluar</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 content">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
            @endif
            @yield('konten_admin')
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>