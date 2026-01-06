<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLocationController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminStockController;
use App\Http\Controllers\AdminVoucherController;

use App\Http\Controllers\MemberController;

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