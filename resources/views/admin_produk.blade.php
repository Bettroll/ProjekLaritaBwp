@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Master Produk</h3>
    <p>Kelola produk roti Larita</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #8B4513 0%, #5D2E0D 100%);">
            <div class="stats-icon"><i class="bi bi-box-seam-fill"></i></div>
            <p class="mb-1">Total Produk</p>
            <h2>{{ $products->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
            <div class="stats-icon"><i class="bi bi-folder-fill"></i></div>
            <p class="mb-1">Kategori</p>
            <h2>{{ $categories->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-check-circle-fill"></i></div>
            <p class="mb-1">Produk Aktif</p>
            <h2>{{ $products->count() }}</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-box-seam-fill me-2"></i>Daftar Produk Roti
        </h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i>Tambah Roti
        </button>
    </div>
    <div class="card-body">
        @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="100">Gambar</th>
                        <th>Nama Roti</th>
                        <th width="150">Kategori</th>
                        <th>Deskripsi</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $item)
                    <tr>
                        <td>
                            <img src="{{ asset('images/products/'.$item->image) }}" 
                                 class="rounded shadow-sm" 
                                 style="width: 70px; height: 70px; object-fit: cover;">
                        </td>
                        <td>
                            <span class="fw-bold">{{ $item->product_name }}</span>
                        </td>
                        <td>
                            <span class="badge" style="background-color: rgba(139, 69, 19, 0.1); color: #8B4513;">
                                <i class="bi bi-tag-fill me-1"></i>{{ $item->category->category_name ?? 'Tanpa Kategori' }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($item->description, 60) }}</small>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <a href="/admin/produk/hapus/{{ $item->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-box-seam" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="mt-3 text-muted">Belum ada produk</h5>
            <p class="text-muted">Klik tombol "Tambah Roti" untuk menambahkan produk baru</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Edit - Dipindahkan ke luar tabel -->
@foreach($products as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="/admin/produk/update/{{ $item->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Produk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="{{ asset('images/products/'.$item->image) }}" 
                                 class="rounded shadow mb-2" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            <p class="text-muted small">Gambar saat ini</p>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Roti</label>
                                <input type="text" name="product_name" class="form-control" value="{{ $item->product_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ $item->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ganti Gambar</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="/admin/produk/tambah" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Roti Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Roti</label>
                            <input type="text" name="product_name" class="form-control" placeholder="Contoh: Roti Coklat Lumer" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi produk roti..." required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Gambar Roti</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG, JPEG. Ukuran maksimal 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection