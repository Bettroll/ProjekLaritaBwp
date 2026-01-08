<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ProductLike;
use App\Models\Product;
use App\Models\Location;

class ProductLikeController extends Controller
{
    // Tampilkan daftar produk favorit milik user
    public function index()
    {
        $userId = Auth::id();
        $selectedLocationId = Session::get('selected_location_id');
        $selectedLocation = $selectedLocationId ? Location::find($selectedLocationId) : null;

        $likeQuery = ProductLike::where('user_id', $userId);
        if ($selectedLocationId) {
            $likeQuery->where('location_id', $selectedLocationId);
        }
        $likes = $likeQuery->get();

        $productIds = $likes->pluck('product_id')->unique()->toArray();
        $products = collect();

        if (!empty($productIds)) {
            if ($selectedLocationId) {
                $products = Product::whereIn('id', $productIds)
                    ->whereHas('locations', function ($q) use ($selectedLocationId) {
                        $q->where('locations.id', $selectedLocationId);
                    })
                    ->with(['locations' => function ($q) use ($selectedLocationId) {
                        $q->where('locations.id', $selectedLocationId);
                    }])
                    ->get();
            } else {
                $products = Product::whereIn('id', $productIds)
                    ->with('locations')
                    ->get();
            }
        }

        return view('member_productlike', compact('products', 'selectedLocation'));
    }

    // Like / Unlike produk pada lokasi terpilih
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $userId = Auth::id();
        $locationId = Session::get('selected_location_id');

        if (!$locationId) {
            return redirect('/home')->with('error', 'Silakan pilih lokasi terlebih dahulu.');
        }

        $productId = (int) $request->product_id;

        $existing = ProductLike::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('location_id', $locationId)
            ->first();

        if ($existing) {
            // Hapus dari favorit (unlike)
            $existing->delete();
            return back()->with('success', 'Produk dihapus dari favorit.');
        } else {
            // Tambah ke favorit (like)
            ProductLike::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'location_id' => $locationId,
            ]);
            return back()->with('success', 'Produk ditambahkan ke favorit.');
        }
    }
}
