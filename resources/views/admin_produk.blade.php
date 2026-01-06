@extends('admin_master')

@section('konten_admin')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Master Produk Roti</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Roti</button>
    </div>
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Roti</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                <tr>
                    <td>
                        <img src="{{ asset('images/products/'.$item->image) }}" width="70" class="rounded">
                    </td>
                    <td><strong>{{ $item->product_name }}</strong></td>
                    <td><span class="badge bg-info text-dark">{{ $item->category->category_name ?? 'Tanpa Kategori' }}</span></td>
                    <td>{{ Str::limit($item->description, 50) }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">Edit</button>
                        <a href="/admin/produk/hapus/{{ $item->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="/admin/produk/update/{{ $item->id }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                    @csrf
                                    <div class="modal-header"><h5>Edit Produk</h5></div>
                                    <div class="modal-body text-start" style="color:#000">
                                        <div class="mb-3">
                                            <label>Nama Roti</label>
                                            <input type="text" name="product_name" class="form-control" value="{{ $item->product_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Kategori</label>
                                            <select name="category_id" class="form-select" required>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="3" required>{{ $item->description }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label>Ganti Gambar (Kosongkan jika tidak diubah)</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update Roti</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/admin/produk/tambah" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header"><h5>Tambah Roti Baru</h5></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Roti</label>
                    <input type="text" name="product_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Gambar Roti</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Roti</button>
            </div>
        </form>
    </div>
</div>
@endsection