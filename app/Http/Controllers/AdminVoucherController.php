<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class AdminVoucherController extends Controller
{
    public function index() {
        // Admin hanya melihat voucher yang kuotanya masih > 0 
        // Sesuai permintaanmu: kalau habis, hilang dari halaman admin
        $vouchers = Voucher::where('quota', '>', 0)->get();
        return view('admin_voucher', compact('vouchers'));
    }

    public function store(Request $request) {
        $request->validate([
            'voucher_name' => 'required',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'min_purchase' => 'required|numeric|min:0',
            'points_needed' => 'required|numeric|min:1',
            'quota' => 'required|numeric|min:1',
        ]);

        Voucher::create($request->all());

        return back()->with('success', 'Voucher terbatas berhasil diterbitkan!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'voucher_name' => 'required',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'min_purchase' => 'required|numeric|min:0',
            'points_needed' => 'required|numeric|min:1',
            'quota' => 'required|numeric|min:1',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());

        return back()->with('success', 'Data voucher berhasil diperbarui!');
    }

    public function destroy($id) {
        Voucher::where('id', $id)->delete();
        return back()->with('success', 'Voucher telah ditarik/dihapus.');
    }
}