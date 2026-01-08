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
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Silakan pilih lokasi terlebih dahulu.'], 400);
            }
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
            $liked = false;
            $message = 'Produk dihapus dari favorit.';
        } else {
            // Tambah ke favorit (like)
            ProductLike::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'location_id' => $locationId,
            ]);
            $liked = true;
            $message = 'Produk ditambahkan ke favorit.';
        }

        // Hitung total like untuk produk ini di lokasi ini
        $likeCount = ProductLike::where('product_id', $productId)
            ->where('location_id', $locationId)
            ->count();

        // Jika AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likeCount' => $likeCount,
                'message' => $message
            ]);
        }

        // Jika bukan AJAX, redirect back
        return back()->with('success', $message);
    }
}
