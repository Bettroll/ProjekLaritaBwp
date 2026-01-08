@extends('member_master')

@section('konten_member')

<!-- 1. PEMILIHAN LOKASI -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm p-3">
            <form action="/set-location" method="POST" class="row align-items-center">
                @csrf
                <div class="col-md-4">
                    <label class="fw-bold mb-1">📍 Pilih Outlet Larita:</label>
                    <select name="location_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Lokasi Belanja --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ $selectedLocation && $selectedLocation->id == $loc->id ? 'selected' : '' }}>
                                {{ $loc->location_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($selectedLocation)
                <div class="col-md-8 mt-3 mt-md-0">
                    <div class="alert alert-info mb-0 py-2">
                        Anda berbelanja di: <strong>{{ $selectedLocation->location_name }}</strong><br>
                        <small>{{ $selectedLocation->address }}</small>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

@if($selectedLocation)
    <!-- 2. FILTER KATEGORI -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="fw-bold mb-3">Kategori Roti</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="/home?category=all" class="btn btn-sm {{ !request('category') || request('category') == 'all' ? 'btn-larita text-white' : 'btn-outline-secondary' }}" style="background-color: {{ !request('category') || request('category') == 'all' ? '#8B4513' : '' }}">Semua</a>
                @foreach($categories as $cat)
                    <a href="/home?category={{ $cat->id }}" class="btn btn-sm {{ request('category') == $cat->id ? 'btn-larita text-white' : 'btn-outline-secondary' }}" style="background-color: {{ request('category') == $cat->id ? '#8B4513' : '' }}">
                        {{ $cat->category_name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 3. DAFTAR PRODUK -->
    <div class="row">
        @forelse($products as $p)
            @php
                $pivotData = $p->locations->first()->pivot;
                $likeCount = \App\Models\ProductLike::where('product_id', $p->id)->where('location_id', $selectedLocation->id)->count();
            @endphp
            <div class="col-md-3 mb-4">
                <div class="card h-100 product-card">
                    <img src="{{ asset('images/products/'.$p->image) }}" class="card-img-top p-2 rounded" style="height: 180px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">{{ $p->product_name }}</h6>
                        <p class="small text-muted mb-2">{{ Str::limit($p->description, 40) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-tag">Rp {{ number_format($pivotData->price) }}</span>
                            @php
                                $liked = Auth::check() ? \App\Models\ProductLike::where('product_id', $p->id)
                                    ->where('location_id', $selectedLocation->id)
                                    ->where('user_id', Auth::id())
                                    ->exists() : false;
                            @endphp
                            <form action="/like/toggle" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                <button type="submit" class="btn btn-link p-0 text-decoration-none" style="border: none; background: none;">
                                    <span style="font-size: 1.2rem; color: {{ $liked ? '#dc3545' : '#6c757d' }};">
                                        {{ $liked ? '❤️' : '🤍' }}
                                    </span>
                                    <small class="text-muted">{{ $likeCount }}</small>
                                </button>
                            </form>
                        </div>
                        <div class="mt-2">
                            <small class="text-secondary">Stok: {{ $pivotData->stock }}</small>
                        </div>
                    </div>
                    <!-- Ganti tombol keranjang lama dengan ini -->
                    <form action="/keranjang/tambah" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <button type="submit" class="btn btn-keranjang w-100">🛒 + Keranjang</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada produk untuk kategori/lokasi ini.</p>
            </div>
        @endforelse
    </div>
@else
    <div class="text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/1048/1048329.png" width="100" class="mb-3 opacity-50">
        <h5 class="text-muted">Silakan pilih lokasi outlet terlebih dahulu untuk melihat menu roti.</h5>
    </div>
@endif

<!-- Modal Sukses Pembayaran -->
@if(session('order_success'))
<div class="modal fade" id="modalOrderSuccess" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow text-center">
            <div class="modal-body py-4">
                <img src="https://cdn-icons-png.flaticon.com/512/5290/5290058.png" width="80" class="mb-3">
                <h4 class="fw-bold text-success">Pembayaran Berhasil!</h4>
                <p class="text-muted small">Pesanan Anda sedang diproses. Silakan cek menu Riwayat Transaksi.</p>
                <button type="button" class="btn btn-success w-100 mt-3" data-bs-dismiss="modal">Mantap!</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Jalankan modal otomatis saat halaman reload jika ada session order_success
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalOrderSuccess'));
        myModal.show();
    });
</script>
@endif

@endsection