@extends('admin_master')

@section('konten_admin')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0">Daftar Roti: {{ $location->location_name }}</h5>
            <small class="text-muted">{{ $location->address }}</small>
        </div>
        <!-- Tombol Tambah Roti dari Master Global -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPilihRoti">
            + Tambah Roti ke Toko
        </button>
    </div>
    <div class="card-body">
        <table class="table align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Gambar</th>
                    <th>Nama Roti</th>
                    <th class="text-center">❤️ Like</th> <!-- Tambah kolom ini -->
                    <th width="150">Harga (Rp)</th>
                    <th width="100">Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($location->products as $p)
                <tr>
                    <td><img src="{{ asset('images/products/'.$p->image) }}" width="50" class="rounded"></td>
                    <td>
                        <strong>{{ $p->product_name }}</strong><br>
                        <small class="badge bg-light text-dark border">{{ $p->category->category_name ?? '-' }}</small>
                    </td>
                    
                    <!-- TAMPILKAN JUMLAH LIKE SPESIFIK LOKASI -->
                    <td class="text-center">
                        <span class="fw-bold text-danger">
                            {{ \App\Models\ProductLike::where('product_id', $p->id)->where('location_id', $location->id)->count() }}
                        </span>
                    </td>

                    <form action="/admin/stok/update/{{ $location->id }}/{{ $p->id }}" method="POST">
                        @csrf
                        <td>
                            <input type="number" name="price" class="form-control form-control-sm" value="{{ $p->pivot->price }}">
                        </td>
                        <td>
                            <input type="number" name="stock" class="form-control form-control-sm" value="{{ $p->pivot->stock }}">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                            <a href="/admin/stok/hapus/{{ $location->id }}/{{ $p->id }}" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </form>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada roti di toko ini. Klik tombol Tambah.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            <a href="/admin/stok" class="btn btn-secondary">Kembali ke Pilih Lokasi</a>
        </div>
    </div>
</div>

<!-- MODAL PILIH ROTI DARI GLOBAL -->
<div class="modal fade" id="modalPilihRoti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/admin/stok/tambah-roti/{{ $location->id }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Pilih Roti dari Master Global</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Roti</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Pilih Roti --</option>
                        @foreach($availableProducts as $ap)
                            <option value="{{ $ap->id }}">{{ $ap->product_name }} ({{ $ap->category->category_name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga di Toko Ini</label>
                        <input type="number" name="price" class="form-control" placeholder="Rp" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stock" class="form-control" value="0" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Masukkan ke Toko</button>
            </div>
        </form>
    </div>
</div>
@endsection