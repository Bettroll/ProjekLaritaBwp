<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class AdminLocationController extends Controller
{
    public function index() {
        $data = Location::all();
        return view('admin_lokasi', compact('data'));
    }

    public function store(Request $request) {
        $request->validate([
            'location_name' => 'required',
            'address' => 'required'
        ]);

        Location::create([
            'location_name' => $request->location_name,
            'address' => $request->address,
            'is_active' => 1 // Otomatis aktif saat dibuat
        ]);

        return back()->with('success', 'Lokasi baru berhasil ditambah!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'location_name' => 'required',
            'address' => 'required'
        ]);

        Location::where('id', $id)->update([
            'location_name' => $request->location_name,
            'address' => $request->address,
            'is_active' => $request->is_active
        ]);

        return back()->with('success', 'Data lokasi berhasil diperbarui!');
    }

    public function destroy($id) {
        Location::where('id', $id)->delete();
        return back()->with('success', 'Lokasi telah dihapus!');
    }
}