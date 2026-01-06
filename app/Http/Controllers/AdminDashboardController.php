<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index() {
        $count = [
            'member' => User::where('role', 'member')->count(),
            'kategori' => Category::count(),
            'lokasi_total' => Location::count(),
            'lokasi_aktif' => Location::where('is_active', 1)->count(), // Lokasi yang sedang buka
            'transaksi' => DB::table('transactions')->count(),
        ];
        return view('admin_dashboard', compact('count'));
    }
}