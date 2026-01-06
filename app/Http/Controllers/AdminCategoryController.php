<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index() {
        $data = Category::all();
        return view('admin_kategori', compact('data'));
    }

    public function store(Request $request) {
        $request->validate(['category_name' => 'required']);
        Category::create($request->all());
        return back()->with('success', 'Kategori berhasil ditambah!');
    }

    public function update(Request $request, $id) {
        $request->validate(['category_name' => 'required']);
        Category::where('id', $id)->update(['category_name' => $request->category_name]);
        return back()->with('success', 'Kategori berhasil diubah!');
    }

    public function destroy($id) {
        Category::where('id', $id)->delete();
        return back()->with('success', 'Kategori dihapus!');
    }
}