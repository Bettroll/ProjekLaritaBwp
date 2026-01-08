<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Larita Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #8B4513;
            --primary-dark: #6B3410;
            --primary-light: #A0522D;
            --secondary-color: #D2691E;
            --accent-color: #F4A460;
            --sidebar-bg: #1a1c23;
            --sidebar-hover: #2d303a;
            --sidebar-active: #8B4513;
            --text-light: #a2a5b9;
            --text-white: #ffffff;
            --bg-light: #f8f9fc;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--bg-light);
            font-size: 14px;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #23242a 100%);
            color: var(--text-white);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-track { background: var(--sidebar-bg); }
        .sidebar::-webkit-scrollbar-thumb { background: var(--primary-color); border-radius: 10px; }
        
        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand h4 {
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
        }
        
        .sidebar-brand .brand-icon {
            font-size: 2rem;
            margin-right: 10px;
        }
        
        .sidebar-menu { padding: 1rem 0; }
        
        .menu-header {
            padding: 0.75rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-light);
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }
        
        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: var(--text-white);
            border-left-color: var(--accent-color);
        }
        
        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(139, 69, 19, 0.3) 0%, transparent 100%);
            color: var(--text-white);
            border-left-color: var(--primary-color);
            font-weight: 500;
        }
        
        .sidebar-menu a i {
            font-size: 1.1rem;
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 1rem 1.5rem;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            width: 100%;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
            color: white;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .page-title {
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .user-name {
            font-weight: 500;
            color: #333;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #888;
        }
        
        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }
        
        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 2rem rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Table Styling */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 500;
            border: none;
            padding: 1rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e3e6f0;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(139, 69, 19, 0.05);
        }
        
        /* Button Styling */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745, #218838);
            border: none;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            border: none;
            color: #000;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border: none;
            color: white;
        }
        
        /* Badge Styling */
        .badge {
            font-weight: 500;
            padding: 0.5em 0.8em;
            border-radius: 6px;
        }
        
        /* Form Styling */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.15);
        }
        
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .modal-header {
            border-bottom: 1px solid #e3e6f0;
            padding: 1.25rem 1.5rem;
        }
        
        .modal-header.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
        }
        
        /* Stats Card */
        .stats-card {
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(30deg);
        }
        
        .stats-card h2 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stats-card p {
            margin: 0;
            opacity: 0.9;
        }
        
        .stats-card .stats-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.3;
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-header h3 {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .page-header p {
            color: #888;
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 0; overflow: hidden; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-icon">🥐</span>
        <h4>LARITA <span class="text-warning">ADMIN</span></h4>
    </div>
    
    <div class="sidebar-menu">
        <div class="menu-header">Menu Utama</div>
        <a href="/admin/dashboard" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        
        <div class="sidebar-divider"></div>
        <div class="menu-header">Master Data</div>
        
        <a href="/admin/kategori" class="{{ Request::is('admin/kategori*') ? 'active' : '' }}">
            <i class="bi bi-folder-fill"></i> Master Kategori
        </a>
        <a href="/admin/lokasi" class="{{ Request::is('admin/lokasi*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt-fill"></i> Master Lokasi
        </a>
        <a href="/admin/produk" class="{{ Request::is('admin/produk*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> Master Produk
        </a>
        <a href="/admin/stok" class="{{ Request::is('admin/stok*') ? 'active' : '' }}">
            <i class="bi bi-stack"></i> Stok & Harga
        </a>
        <a href="/admin/voucher" class="{{ Request::is('admin/voucher*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated-fill"></i> Master Voucher
        </a>
        
        <div class="sidebar-divider"></div>
        <div class="menu-header">Operasional</div>
        
        <a href="/admin/pesanan" class="{{ Request::is('admin/pesanan*') ? 'active' : '' }}">
            <i class="bi bi-cart-check-fill"></i> Pesanan Masuk
        </a>
        <a href="/admin/laporan" class="{{ Request::is('admin/laporan*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Laporan Penjualan
        </a>
    </div>
    
    <div class="sidebar-footer">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <div class="top-navbar">
        <h5 class="page-title">
            @if(Request::is('admin/dashboard')) Dashboard
            @elseif(Request::is('admin/kategori*')) Master Kategori
            @elseif(Request::is('admin/lokasi*')) Master Lokasi
            @elseif(Request::is('admin/produk*')) Master Produk
            @elseif(Request::is('admin/stok*')) Stok & Harga
            @elseif(Request::is('admin/voucher*')) Master Voucher
            @elseif(Request::is('admin/pesanan*')) Pesanan Masuk
            @elseif(Request::is('admin/laporan*')) Laporan Penjualan
            @else Admin Panel
            @endif
        </h5>
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('konten_admin')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>