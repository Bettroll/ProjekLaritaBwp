<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // app/Http/Controllers/TransactionController.php
    public function index(Request $request) {
        $search = $request->get('search');
        
        // Ambil transaksi user yang sedang login
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['location', 'details.product']) // Eager loading biar gak lemot
            ->when($search, function($query) use ($search) {
                return $query->where('invoice_number', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('member_transaksi', compact('transactions'));
    }
}
