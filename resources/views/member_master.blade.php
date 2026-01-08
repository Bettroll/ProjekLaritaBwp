<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Larita Bakery - Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar-larita { background-color: #8B4513; }
        .nav-link { color: rgba(255,255,255,0.8) !important; }
        .nav-link:hover, .nav-link.active { color: #fff !important; font-weight: 600; }
        .btn-keranjang { background-color: #ffc107; color: #000; font-weight: bold; border: none; }
        .product-card { border: none; border-radius: 12px; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
        .price-tag { color: #8B4513; font-weight: bold; font-size: 1.1rem; }
    </style>
</head>
<body>

    <!-- Navigasi Member -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-larita sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/home">
                🥐 LARITA <span class="text-warning">BAKERY</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link {{ Request::is('home*') ? 'active' : '' }}" href="/home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ Request::is('voucher*') ? 'active' : '' }}" href="/voucher">Voucher</a></li>
                    <li class="nav-item"><a class="nav-link {{ Request::is('riwayat-transaksi*') ? 'active' : '' }}" href="/riwayat-transaksi">Transaksi</a></li>
                    <li class="nav-item"><a class="nav-link {{ Request::is('productlike*') ? 'active' : '' }}" href="/productlike">Product Likes</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <!-- Di dalam member_master.blade.php -->
                    <a href="/keranjang" class="btn btn-warning btn-sm me-3 position-relative">
                        🛒 Keranjang
                        @if(Auth::check())
                            @php
                                // Hitung jumlah jenis produk unik di keranjang untuk lokasi aktif
                                $cartCount = Auth::user()->carts()
                                            ->where('location_id', session('selected_location_id'))
                                            ->count();
                            @endphp
                            
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endif
                    </a>
                    <div class="dropdown">
                        <a class="text-white text-decoration-none dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Hi, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item small" href="#">Poin: <strong>{{ Auth::user()->points }}</strong></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container py-4">
        @yield('konten_member')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>