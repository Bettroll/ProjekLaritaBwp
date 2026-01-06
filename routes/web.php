<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLocationController;
use App\Http\Controllers\AdminProductController;

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
    Route::get('/home', function () {
        return view('member_home');
    });
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
});