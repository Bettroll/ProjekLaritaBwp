<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class AdminProductController extends Controller
{
    public function index() {
        $products = Product::with('category')->get(); // Mengambil produk beserta nama kategorinya
        $categories = Category::all(); // Untuk pilihan di modal tambah/edit
        return view('admin_produk', compact('products', 'categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'product_name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // Proses Upload Gambar
        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('images/products'), $imageName);

        Product::create([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return back()->with('success', 'Roti baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            File::delete(public_path('images/products/'.$product->image));

            // Upload gambar baru
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        $product->product_name = $request->product_name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->save();

        return back()->with('success', 'Data roti berhasil diperbarui!');
    }

    public function destroy($id) {
        Product::where('id', $id)->delete(); // Ini otomatis Soft Delete
        return back()->with('success', 'Roti berhasil dihapus (Soft Delete)!');
    }
}