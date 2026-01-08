<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLocationController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminStockController;
use App\Http\Controllers\AdminVoucherController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MemberVoucherController;

use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductLikeController;

// Halaman awal
Route::get('/', function () { return redirect('/login'); });

// Halaman Login & Register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// --- GROUP MEMBER (Hanya bisa dibuka kalau login sebagai member) ---
Route::middleware(['role:member'])->group(function () {
    Route::get('/home', [MemberController::class, 'index']);
    Route::post('/set-location', [MemberController::class, 'setLocation']);

    // Product Like (Favorite)
    Route::get('/productlike', [ProductLikeController::class, 'index']);
    Route::post('/like/toggle', [ProductLikeController::class, 'toggle']);

    // Route Keranjang
    Route::get('/keranjang', [CartController::class, 'index']);
    Route::post('/keranjang/tambah', [CartController::class, 'addToCart']);
    Route::post('/keranjang/update-qty', [CartController::class, 'updateQuantity']);
    Route::get('/keranjang/hapus/{id}', [CartController::class, 'delete']);

    Route::get('/checkout', [MemberController::class, 'checkoutIndex']);

    // Route Voucher
    Route::get('/voucher', [MemberVoucherController::class, 'index']);
    Route::post('/voucher/tukar/{id}', [MemberVoucherController::class, 'redeem']);

    Route::post('/checkout/proses', [MemberController::class, 'placeOrder']);

    // Route history
    Route::get('/riwayat-transaksi', [MemberController::class, 'history']);
    Route::get('/riwayat-transaksi/detail/{id}', [MemberController::class, 'showDetail']);
});



// --- GROUP ADMIN (Hanya bisa dibuka kalau login sebagai admin) ---
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

    // Master Kategori
    Route::get('/admin/kategori', [AdminCategoryController::class, 'index']);
    Route::post('/admin/kategori/tambah', [AdminCategoryController::class, 'store']);
    Route::post('/admin/kategori/update/{id}', [AdminCategoryController::class, 'update']);
    Route::get('/admin/kategori/hapus/{id}', [AdminCategoryController::class, 'destroy']);
    
    // Nanti kita tambah route Lokasi dan Produk di sini...
    Route::get('/admin/lokasi', [AdminLocationController::class, 'index']);
    Route::post('/admin/lokasi/tambah', [AdminLocationController::class, 'store']);
    Route::post('/admin/lokasi/update/{id}', [AdminLocationController::class, 'update']);
    Route::get('/admin/lokasi/hapus/{id}', [AdminLocationController::class, 'destroy']);

    Route::get('/admin/produk', [AdminProductController::class, 'index']);
    Route::post('/admin/produk/tambah', [AdminProductController::class, 'store']);
    Route::post('/admin/produk/update/{id}', [AdminProductController::class, 'update']);
    Route::get('/admin/produk/hapus/{id}', [AdminProductController::class, 'destroy']);

    Route::get('/admin/stok', [AdminStockController::class, 'index']);
    Route::get('/admin/stok/kelola/{location_id}', [AdminStockController::class, 'manage']);
    Route::post('/admin/stok/tambah-roti/{location_id}', [AdminStockController::class, 'addProduct']);
    Route::post('/admin/stok/update/{location_id}/{product_id}', [AdminStockController::class, 'updateStock']);
    Route::get('/admin/stok/hapus/{location_id}/{product_id}', [AdminStockController::class, 'removeProduct']);

    Route::get('/admin/voucher', [AdminVoucherController::class, 'index']);
    Route::post('/admin/voucher/tambah', [AdminVoucherController::class, 'store']);
    Route::post('/admin/voucher/update/{id}', [AdminVoucherController::class, 'update']);
    Route::get('/admin/voucher/hapus/{id}', [AdminVoucherController::class, 'destroy']);
});