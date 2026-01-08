@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Kelola Stok: {{ $location->location_name }}</h3>
    <p><i class="bi bi-pin-map me-1"></i>{{ $location->address }}</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #8B4513 0%, #5D2E0D 100%);">
            <div class="stats-icon"><i class="bi bi-box-seam-fill"></i></div>
            <p class="mb-1">Total Produk</p>
            <h2>{{ $location->products->count() }}</h2>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-stack"></i></div>
            <p class="mb-1">Total Stok</p>
            <h2>{{ $location->products->sum('pivot.stock') }}</h2>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);">
            <div class="stats-icon"><i class="bi bi-heart-fill"></i></div>
            <p class="mb-1">Total Like</p>
            <h2>{{ \App\Models\ProductLike::where('location_id', $location->id)->count() }}</h2>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
            <div class="stats-icon"><i class="bi bi-plus-circle-fill"></i></div>
            <p class="mb-1">Belum Ditambah</p>
            <h2>{{ $availableProducts->count() }}</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-box-seam-fill me-2"></i>Daftar Produk di {{ $location->location_name }}
        </h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPilihRoti">
            <i class="bi bi-plus-lg me-1"></i>Tambah Roti ke Toko
        </button>
    </div>
    <div class="card-body">
        @if($location->products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="80">Gambar</th>
                        <th>Nama Roti</th>
                        <th width="80" class="text-center">Like</th>
                        <th width="150">Harga (Rp)</th>
                        <th width="100">Stok</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($location->products as $p)
                    <tr>
                        <td>
                            <img src="{{ asset('images/products/'.$p->image) }}" 
                                 class="rounded shadow-sm" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td>
                            <span class="fw-bold">{{ $p->product_name }}</span>
                            <br>
                            <span class="badge" style="background-color: rgba(139, 69, 19, 0.1); color: #8B4513;">
                                <i class="bi bi-tag-fill me-1"></i>{{ $p->category->category_name ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger">
                                <i class="bi bi-heart-fill me-1"></i>{{ \App\Models\ProductLike::where('product_id', $p->id)->where('location_id', $location->id)->count() }}
                            </span>
                        </td>
                        <form action="/admin/stok/update/{{ $location->id }}/{{ $p->id }}" method="POST">
                            @csrf
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control" value="{{ $p->pivot->price }}" required>
                                </div>
                            </td>
                            <td>
                                <input type="number" name="stock" class="form-control form-control-sm" value="{{ $p->pivot->stock }}" required>
                            </td>
                            <td class="text-center">
                                <button type="submit" class="btn btn-success btn-sm me-1" title="Simpan">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <a href="/admin/stok/hapus/{{ $location->id }}/{{ $p->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini dari toko?')" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-box-seam" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="mt-3 text-muted">Belum ada roti di toko ini</h5>
            <p class="text-muted">Klik tombol "Tambah Roti ke Toko" untuk menambahkan produk</p>
        </div>
        @endif

        <div class="mt-4">
            <a href="/admin/stok" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Pilih Lokasi
            </a>
        </div>
    </div>
</div>

<!-- Modal Pilih Roti -->
<div class="modal fade" id="modalPilihRoti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/stok/tambah-roti/{{ $location->id }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Roti ke {{ $location->location_name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($availableProducts->count() > 0)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Roti</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Pilih Roti --</option>
                            @foreach($availableProducts as $ap)
                                <option value="{{ $ap->id }}">{{ $ap->product_name }} ({{ $ap->category->category_name ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga di Toko Ini</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" value="0" required>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h6 class="mt-2">Semua produk sudah ditambahkan!</h6>
                        <p class="text-muted small">Tidak ada produk yang tersisa untuk ditambahkan ke lokasi ini</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    @if($availableProducts->count() > 0)
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Masukkan ke Toko
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection