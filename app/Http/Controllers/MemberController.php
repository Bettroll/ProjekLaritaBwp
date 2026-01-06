<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Support\Facades\Session;

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
}