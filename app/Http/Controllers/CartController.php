<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index() {
        $location_id = Session::get('selected_location_id');
        if (!$location_id) return redirect('/home')->with('error', 'Pilih lokasi dulu!');

        // Ambil data keranjang user untuk lokasi yang sedang dipilih
        $cartItems = Cart::where('user_id', Auth::id())
                         ->where('location_id', $location_id)
                         ->with('product.locations')
                         ->get();

        return view('member_cart', compact('cartItems'));
    }

    public function addToCart(Request $request) {
        $location_id = Session::get('selected_location_id');
        
        // Cek apakah produk sudah ada di keranjang
        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->where('location_id', $location_id)
                    ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'location_id' => $location_id,
                'quantity' => 1
            ]);
        }

        return redirect('/keranjang');
    }

    // Fungsi AJAX untuk update quantity (Poin 15)
    public function updateQuantity(Request $request) {
        $cart = Cart::find($request->cart_id);
        if($cart) {
            $cart->update(['quantity' => $request->quantity]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function delete($id) {
        Cart::destroy($id);
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}