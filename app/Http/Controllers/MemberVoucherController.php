<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberVoucherController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil voucher yang:
        // 1. Kuotanya masih ada (> 0)
        // 2. BELUM PERNAH ditukar oleh user ini
        $vouchers = Voucher::where('quota', '>', 0)
            ->whereDoesntHave('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        return view('member_voucher', compact('vouchers', 'user'));
    }

    public function redeem($id)
    {
        $user = User::find(Auth::id());
        $voucher = Voucher::findOrFail($id);

        // 1. Cek apakah user sudah pernah tukar (Security check)
        $alreadyOwned = DB::table('user_vouchers')
                            ->where('user_id', $user->id)
                            ->where('voucher_id', $id)
                            ->exists();
        
        if ($alreadyOwned) {
            return back()->with('error', 'Anda sudah memiliki voucher ini!');
        }

        // 2. Cek apakah poin cukup
        if ($user->points < $voucher->points_needed) {
            return back()->with('error', 'Poin Anda tidak cukup untuk menukar voucher ini.');
        }

        // 3. Cek apakah kuota global masih ada
        if ($voucher->quota <= 0) {
            return back()->with('error', 'Maaf, kuota voucher ini sudah habis!');
        }

        // 4. Proses Transaksi Penukaran (Gunakan Database Transaction agar aman)
        DB::transaction(function () use ($user, $voucher) {
            // Kurangi poin user
            $user->points -= $voucher->points_needed;
            $user->save();

            // Kurangi kuota voucher
            $voucher->quota -= 1;
            $voucher->save();

            // Masukkan ke koleksi voucher user
            $user->vouchers()->attach($voucher->id, [
                'is_used' => 0,
                'redeemed_at' => now()
            ]);
        });

        return back()->with('success', 'Berhasil! Voucher ' . $voucher->voucher_name . ' kini milik Anda.');
    }
}