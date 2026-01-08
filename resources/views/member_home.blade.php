@extends('member_master')

@section('konten_member')

<!-- 1. PEMILIHAN LOKASI -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card p-4">
            <form action="/set-location" method="POST" class="row align-items-center g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-2">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>Pilih Outlet Larita
                    </label>
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
                <div class="col-md-8">
                    <div class="alert alert-info mb-0 d-flex align-items-center">
                        <i class="bi bi-shop fs-4 me-3"></i>
                        <div>
                            <strong>{{ $selectedLocation->location_name }}</strong><br>
                            <small class="text-muted">{{ $selectedLocation->address }}</small>
                        </div>
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
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-grid-3x3-gap-fill me-2" style="color: #8B4513;"></i>Kategori Roti
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="/home?category=all" class="btn btn-sm {{ !request('category') || request('category') == 'all' ? 'btn-larita' : 'btn-outline-secondary' }}">
                        <i class="bi bi-collection me-1"></i>Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="/home?category={{ $cat->id }}" class="btn btn-sm {{ request('category') == $cat->id ? 'btn-larita' : 'btn-outline-secondary' }}">
                            {{ $cat->category_name }}
                        </a>
                    @endforeach
                </div>
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
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card h-100 product-card">
                    <div class="position-relative overflow-hidden" style="border-radius: 16px 16px 0 0;">
                        <img src="{{ asset('images/products/'.$p->image) }}" class="card-img-top p-2" style="height: 180px; object-fit: cover;">
                        @if($pivotData->stock <= 5 && $pivotData->stock > 0)
                            <span class="badge bg-warning text-dark position-absolute" style="top: 10px; right: 10px;">
                                Sisa {{ $pivotData->stock }}
                            </span>
                        @elseif($pivotData->stock == 0)
                            <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">
                                Habis
                            </span>
                        @endif
                    </div>
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
                            <button type="button" class="btn btn-link p-0 text-decoration-none like-btn" 
                                    style="border: none; background: none;" 
                                    data-product-id="{{ $p->id }}" 
                                    data-liked="{{ $liked ? '1' : '0' }}">
                                <span class="heart-icon" style="font-size: 1.3rem;">
                                    <i class="bi {{ $liked ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}"></i>
                                </span>
                                <small class="text-muted like-count ms-1">{{ $likeCount }}</small>
                            </button>
                        </div>
                        <div class="mt-2">
                            <small class="text-secondary">
                                <i class="bi bi-box-seam me-1"></i>Stok: {{ $pivotData->stock }}
                            </small>
                        </div>
                    </div>
                    <form action="/keranjang/tambah" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <button type="submit" class="btn btn-keranjang w-100" {{ $pivotData->stock == 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
                <h5 class="mt-3 text-muted">Belum ada produk</h5>
                <p class="text-muted">Tidak ada produk untuk kategori/lokasi ini.</p>
            </div>
        @endforelse
    </div>
@else
    <div class="text-center py-5">
        <div class="card p-5">
            <i class="bi bi-geo-alt" style="font-size: 5rem; color: #ddd;"></i>
            <h4 class="mt-4 text-muted fw-bold">Pilih Lokasi Terlebih Dahulu</h4>
            <p class="text-muted">Silakan pilih outlet Larita di atas untuk melihat menu roti yang tersedia.</p>
        </div>
    </div>
@endif

<!-- Modal Sukses Pembayaran -->
@if(session('order_success'))
<div class="modal fade" id="modalOrderSuccess" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow text-center" style="border-radius: 20px;">
            <div class="modal-body py-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h4 class="fw-bold text-success mb-2">Pembayaran Berhasil!</h4>
                <p class="text-muted">Pesanan Anda sedang diproses.<br>Silakan cek menu Riwayat Transaksi.</p>
                <button type="button" class="btn btn-success btn-lg px-5 mt-3" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-1"></i>Mantap!
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalOrderSuccess'));
        myModal.show();
    });
</script>
@endif

<!-- AJAX Like Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.like-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                const heartIcon = this.querySelector('.heart-icon i');
                const likeCountEl = this.querySelector('.like-count');
                const isLiked = this.getAttribute('data-liked') === '1';
                
                fetch('/like/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.liked) {
                            heartIcon.className = 'bi bi-heart-fill text-danger';
                            btn.setAttribute('data-liked', '1');
                        } else {
                            heartIcon.className = 'bi bi-heart text-secondary';
                            btn.setAttribute('data-liked', '0');
                        }
                        likeCountEl.textContent = data.likeCount;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>

@endsection