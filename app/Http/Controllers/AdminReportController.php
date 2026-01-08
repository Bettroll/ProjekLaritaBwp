<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::all();

        // Query dasar: Ambil transaksi yang sudah selesai
        $query = Transaction::where('status', 'completed')->with(['user', 'location']);

        // Filter Berdasarkan Tanggal Mulai
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // Filter Berdasarkan Tanggal Selesai
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter Berdasarkan Lokasi
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $transactions = $query->latest()->get();

        // Hitung Ringkasan (Summary)
        $totalRevenue = $transactions->sum('final_price');
        $totalSales = $transactions->count();

        return view('admin_laporan', compact('transactions', 'locations', 'totalRevenue', 'totalSales'));
    }
}