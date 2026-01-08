<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Larita Bakery - Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8B4513;
            --primary-dark: #5D2E0D;
            --primary-light: #D2691E;
            --accent-color: #ffc107;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --info-color: #36b9cc;
        }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f8f9fa; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-wrapper {
            flex: 1;
        }
        
        /* Navbar Styles */
        .navbar-larita { 
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 0.8rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .navbar-brand .brand-icon {
            font-size: 1.6rem;
        }
        
        .nav-link { 
            color: rgba(255,255,255,0.85) !important; 
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 0 2px;
        }
        
        .nav-link:hover { 
            color: #fff !important; 
            background: rgba(255,255,255,0.1);
        }
        
        .nav-link.active { 
            color: #fff !important; 
            background: rgba(255,255,255,0.2);
            font-weight: 600; 
        }
        
        .nav-link i {
            margin-right: 5px;
        }
        
        /* Cart Button */
        .btn-cart {
            background: var(--accent-color);
            color: #000;
            font-weight: 600;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .btn-cart:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }
        
        /* User Dropdown */
        .user-dropdown {
            background: rgba(255,255,255,0.1);
            border-radius: 25px;
            padding: 0.4rem 1rem;
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: #f8f9fa;
        }
        
        /* Product Card Styles */
        .product-card { 
            border: none; 
            border-radius: 16px; 
            transition: all 0.3s ease; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .product-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 12px 30px rgba(0,0,0,0.12); 
        }
        
        .product-card .card-img-top {
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .price-tag { 
            color: var(--primary-color); 
            font-weight: 700; 
            font-size: 1.15rem; 
        }
        
        /* Button Styles */
        .btn-keranjang { 
            background: linear-gradient(135deg, var(--accent-color) 0%, #e0a800 100%);
            color: #000; 
            font-weight: 600; 
            border: none;
            border-radius: 0 0 16px 16px;
            padding: 0.8rem;
            transition: all 0.3s ease;
        }
        
        .btn-keranjang:hover {
            background: linear-gradient(135deg, #e0a800 0%, #d49a00 100%);
            color: #000;
        }
        
        .btn-larita {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: #fff;
            border: none;
            font-weight: 500;
        }
        
        .btn-larita:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #4a2409 100%);
            color: #fff;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: 16px 16px 0 0 !important;
        }
        
        /* Badge Styles */
        .badge-points {
            background: linear-gradient(135deg, var(--accent-color) 0%, #e0a800 100%);
            color: #000;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        
        /* Form Styles */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }
        
        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
        }
        
        .alert-info {
            background: rgba(54, 185, 204, 0.1);
            color: #258391;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: rgba(255,255,255,0.9);
            padding: 2rem 0;
            margin-top: auto;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 1.5rem;
        }
        
        .page-header h3 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .page-header p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Stats Card */
        .stats-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card h2 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0;
        }
        
        .stats-card .stats-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.3;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
    <!-- Navigasi Member -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-larita sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/home">
                <span class="brand-icon">🥐</span>
                LARITA <span class="text-warning">BAKERY</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('home*') ? 'active' : '' }}" href="/home">
                            <i class="bi bi-house-door-fill"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('voucher*') ? 'active' : '' }}" href="/voucher">
                            <i class="bi bi-ticket-perforated-fill"></i>Voucher
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('riwayat-transaksi*') ? 'active' : '' }}" href="/riwayat-transaksi">
                            <i class="bi bi-clock-history"></i>Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('productlike*') ? 'active' : '' }}" href="/productlike">
                            <i class="bi bi-heart-fill"></i>Favorit
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <!-- Keranjang Button -->
                    <a href="/keranjang" class="btn btn-cart position-relative">
                        <i class="bi bi-cart3 me-1"></i>Keranjang
                        @if(Auth::check())
                            @php
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
                    
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <a class="text-white text-decoration-none dropdown-toggle user-dropdown d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li>
                                <div class="dropdown-item-text text-center py-2">
                                    <div class="badge-points mb-2">
                                        <i class="bi bi-star-fill me-1"></i>{{ number_format(Auth::user()->points) }} Poin
                                    </div>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
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
</div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-1"><span class="me-2">🥐</span>LARITA BAKERY</h5>
                    <p class="mb-0 opacity-75 small">Roti segar berkualitas untuk keluarga Anda</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <small class="opacity-75">© {{ date('Y') }} Larita Bakery. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>