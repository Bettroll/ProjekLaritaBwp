<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminStockController extends Controller
{
    public function index() {
        $locations = Location::all();
        return view('admin_stok_index', compact('locations'));
    }

    // Menampilkan roti yang SUDAH ADA di lokasi tersebut
    public function manage($location_id) {
        $location = Location::with('products')->findOrFail($location_id);
        
        // Ambil roti global yang BELUM ADA di lokasi ini (untuk pilihan di modal tambah)
        $existingProductIds = $location->products->pluck('id')->toArray();
        $availableProducts = Product::whereNotIn('id', $existingProductIds)->get();

        return view('admin_stok_manage', compact('location', 'availableProducts'));
    }

    // Menambahkan roti baru ke lokasi (Pilih dari Global)
    public function addProduct(Request $request, $location_id) {
        $request->validate([
            'product_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $location = Location::findOrFail($location_id);
        
        // attach() digunakan untuk menambah relasi baru di pivot
        $location->products()->attach($request->product_id, [
            'price' => $request->price,
            'stock' => $request->stock
        ]);

        return back()->with('success', 'Roti berhasil ditambahkan ke toko ini!');
    }

    // Update stok/harga roti yang sudah ada
    public function updateStock(Request $request, $location_id, $product_id) {
        $location = Location::findOrFail($location_id);
        
        $location->products()->updateExistingPivot($product_id, [
            'price' => $request->price,
            'stock' => $request->stock
        ]);

        return back()->with('success', 'Stok dan harga berhasil diperbarui!');
    }

    // Menghapus roti dari lokasi ini saja (tidak menghapus global)
    public function removeProduct($location_id, $product_id) {
        $location = Location::findOrFail($location_id);
        $location->products()->detach($product_id);
        return back()->with('success', 'Roti telah ditarik dari toko ini.');
    }
}