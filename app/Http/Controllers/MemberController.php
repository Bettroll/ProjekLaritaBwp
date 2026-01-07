<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;


class MemberController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::where('is_active', 1)->get();
        $categories = Category::all();
        
        // Mengambil lokasi yang dipilih dari Session (Poin 10)
        $selectedLocationId = Session::get('selected_location_id');
        $selectedLocation = null;
        $products = collect();

        if ($selectedLocationId) {
            $selectedLocation = Location::find($selectedLocationId);
            
            // Query produk berdasarkan lokasi dan filter kategori
            $query = Product::whereHas('locations', function($q) use ($selectedLocationId) {
                $q->where('locations.id', $selectedLocationId);
            })->with(['locations' => function($q) use ($selectedLocationId) {
                $q->where('locations.id', $selectedLocationId);
            }]);

            if ($request->category && $request->category != 'all') {
                $query->where('category_id', $request->category);
            }

            $products = $query->get();
        }

        return view('member_home', compact('locations', 'categories', 'products', 'selectedLocation'));
    }

    // Fungsi untuk menyimpan lokasi pilihan ke Session
    public function setLocation(Request $request)
    {
        Session::put('selected_location_id', $request->location_id);
        return redirect('/home')->with('success', 'Lokasi berhasil dipilih!');
    }

    public function checkoutIndex(Request $request)
    {
        $selectedIds = $request->cart_ids;
        if (!$selectedIds) {
            return redirect('/keranjang')->with('error', 'Pilih minimal satu produk untuk checkout!');
        }

        $location_id = session('selected_location_id');
        
        // 1. Ambil item keranjang yang dipilih saja
        $cartItems = \App\Models\Cart::whereIn('id', $selectedIds)
                    ->where('user_id', \Auth::id())
                    ->with('product.locations')
                    ->get();

        // 2. Ambil data lokasi aktif
        $location = \App\Models\Location::find($location_id);

        // 3. Ambil voucher yang dimiliki member dan BELUM digunakan (is_used = 0)
        $userVouchers = \Auth::user()->vouchers()->wherePivot('is_used', 0)->get();

        return view('member_checkout', compact('cartItems', 'location', 'userVouchers'));
    }

    public function placeOrder(Request $request)
{
    $user = Auth::user();
    $location_id = session('selected_location_id');
    $cartItems = Cart::whereIn('id', $request->cart_ids)->with('product.locations')->get();

    DB::transaction(function () use ($request, $user, $location_id, $cartItems) {
        
        // --- LOGIKA BARU UNTUK MENANGKAP INFO PENGIRIMAN ---
        $infoPengiriman = "";

        if ($request->shipping_method == 'pickup') {
            // Jika ambil sendiri, ambil data dari form_pickup
            $nama = $request->pickup_name ?? $user->name;
            $jam = $request->pickup_time ?? '-';
            $infoPengiriman = "METODE: AMBIL SENDIRI. Nama Pengambil: $nama, Estimasi Jam: $jam";
        } else {
            // Jika diantar, ambil data dari form_delivery
            $alamat = $request->delivery_address ?? 'Alamat tidak diisi';
            $penerima = $request->receiver_name ?? $user->name;
            $waktu = $request->delivery_time ?? '-';
            $infoPengiriman = "METODE: DIANTAR. Alamat: $alamat, Penerima: $penerima, Waktu Tiba: $waktu";
        }

        // Hitung Subtotal (sama seperti sebelumnya)
        $subtotal = 0;
        foreach($cartItems as $item) {
            $price = $item->product->locations->where('id', $location_id)->first()->pivot->price;
            $subtotal += ($price * $item->quantity);
        }

        // Hitung Diskon Voucher (sama seperti sebelumnya)
        $discount = 0;
        if ($request->voucher_id) {
            $voucher = \App\Models\Voucher::find($request->voucher_id);
            if ($subtotal >= $voucher->min_purchase) {
                $discount = ($subtotal * $voucher->discount_percent) / 100;
                $user->vouchers()->updateExistingPivot($request->voucher_id, ['is_used' => 1]);
            }
        }

        $shipping = ($request->shipping_method == 'delivery') ? 10000 : 0;
        $totalFinal = $subtotal + $shipping - $discount;

        // 1. SIMPAN TRANSAKSI
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'location_id' => $location_id,
            'invoice_number' => 'INV-' . time(),
            'total_price' => $subtotal,
            'discount_amount' => $discount,
            'final_price' => $totalFinal,
            'shipping_method' => $request->shipping_method,
            'shipping_details' => $infoPengiriman, // <-- SEKARANG SUDAH ADA ISINYA
            'status' => 'pending',
            'points_earned' => floor($totalFinal / 1000),
        ]);

        // ... (Simpan Detail & Potong Stok tetap sama seperti kode sebelumnya) ...
        foreach($cartItems as $item) {
            $price = $item->product->locations->where('id', $location_id)->first()->pivot->price;
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item->product_id,
                'price_at_purchase' => $price,
                'quantity' => $item->quantity,
                'subtotal' => $price * $item->quantity
            ]);

            // Potong Stok
            $location = \App\Models\Location::find($location_id);
            $currentStock = $location->products()->where('product_id', $item->product_id)->first()->pivot->stock;
            $location->products()->updateExistingPivot($item->product_id, [
                'stock' => $currentStock - $item->quantity
            ]);
        }

        $user->points += $transaction->points_earned;
        $user->save();
        Cart::whereIn('id', $request->cart_ids)->delete();
    });

    return redirect('/home')->with('order_success', true);
}
}