@extends('member_master')

@section('konten_member')
<style>
    .stepper-wrapper { 
        display: flex; 
        justify-content: space-between; 
        position: relative; 
        margin-bottom: 30px; 
        z-index: 1;
    }

    .stepper-item { 
        position: relative; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        flex: 1; 
    }

    .stepper-item::before { 
        position: absolute; 
        content: ""; 
        border-bottom: 3px solid #e0e0e0;
        width: 100%; 
        top: 25px;
        left: -50%; 
        z-index: 1;
    }

    .stepper-item:first-child::before { content: none; }

    .step-counter { 
        position: relative;
        z-index: 2;
        width: 50px; 
        height: 50px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 50%; 
        background-color: #e0e0e0;
        color: white; 
        margin-bottom: 6px; 
        font-size: 20px; 
        transition: 0.3s;
        border: 3px solid white;
    }

    .stepper-item.active .step-counter { 
        background-color: #8B4513; 
        box-shadow: 0 0 0 3px white;
    }

    .stepper-item.active::before { 
        border-color: #8B4513;
    }

    .stepper-item.active .step-name { 
        color: #8B4513; 
        font-weight: bold; 
    }

    .info-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .product-item {
        display: flex;
        align-items: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .product-image {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .summary-box {
        background: linear-gradient(135deg, #8B4513 0%, #5D2E0D 100%);
        border-radius: 12px;
        padding: 20px;
        color: white;
    }
</style>

<div class="page-header">
    <a href="/riwayat-transaksi" class="btn btn-link text-decoration-none p-0 mb-2" style="color: #8B4513;">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat
    </a>
    <h3><i class="bi bi-receipt me-2"></i>Detail Transaksi</h3>
    <p>{{ $trx->invoice_number }}</p>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        
        <!-- Progress Status (Stepper) -->
        @php
            $statuses = ['pending', 'processing', 'ready', 'completed'];
            $currentIdx = array_search($trx->status, $statuses);
            $icons = ['bi-hourglass-split', 'bi-arrow-repeat', 'bi-bag-check', 'bi-check-circle-fill'];
            $labels = ['Pending', 'Diproses', 'Siap Ambil', 'Selesai'];
        @endphp

        <div class="stepper-wrapper mt-2">
            @foreach($statuses as $index => $statusName)
                <div class="stepper-item {{ $index <= $currentIdx ? 'active' : '' }}">
                    <div class="step-counter">
                        <i class="bi {{ $icons[$index] }}"></i>
                    </div>
                    <div class="step-name small">{{ $labels[$index] }}</div>
                </div>
            @endforeach
        </div>

        <div class="text-center mb-4">
            @if($trx->status == 'completed')
                <span class="badge bg-success px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i>Pesanan Sudah Diambil
                </span>
            @elseif($trx->status == 'ready')
                <span class="badge bg-primary px-3 py-2">
                    <i class="bi bi-bag-check me-1"></i>Pesanan Siap Diambil
                </span>
            @elseif($trx->status == 'processing')
                <span class="badge bg-info px-3 py-2">
                    <i class="bi bi-arrow-repeat me-1"></i>Sedang Diproses
                </span>
            @else
                <span class="badge bg-warning px-3 py-2">
                    <i class="bi bi-hourglass-split me-1"></i>Menunggu Konfirmasi
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Informasi Pesanan & Lokasi -->
<div class="row mb-4">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2" style="color: #8B4513;"></i>Informasi Pesanan
                </h6>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="text-muted">Nomor Invoice</span>
                    <span class="fw-bold">{{ $trx->invoice_number }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Tanggal Transaksi</span>
                    <span class="fw-bold">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Status Transaksi</span>
                    <span class="badge bg-{{ $trx->status == 'completed' ? 'success' : ($trx->status == 'ready' ? 'primary' : ($trx->status == 'processing' ? 'info' : 'warning')) }}">
                        {{ ucfirst($trx->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-geo-alt me-2" style="color: #8B4513;"></i>Lokasi Pengambilan
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-light rounded-circle p-2 me-3">
                        <i class="bi bi-shop" style="color: #8B4513; font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">{{ $trx->location->location_name }}</h6>
                        <p class="text-muted small mb-0">{{ $trx->location->address }}</p>
                    </div>
                </div>
                <div class="info-section">
                    <small class="text-muted d-block mb-1">Info Pengambil:</small>
                    <span class="fw-semibold">{{ $trx->shipping_details ?? 'Ambil di Toko' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Produk -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">
            <i class="bi bi-box-seam me-2" style="color: #8B4513;"></i>Detail Pesanan ({{ $trx->details->count() }} produk)
        </h6>
    </div>
    <div class="card-body">
        @foreach($trx->details as $item)
            <div class="product-item">
                <div class="product-image me-3">
                    @if($item->product->image)
                        <img src="{{ asset('images/products/' . $item->product->image) }}" 
                             alt="{{ $item->product->product_name }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 bg-secondary text-white">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold">{{ $item->product->product_name }}</h6>
                    <small class="text-muted">Harga: Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</small>
                </div>
                <div class="text-center px-3">
                    <span class="badge bg-light text-dark">{{ $item->quantity }} pcs</span>
                </div>
                <div class="text-end" style="min-width: 120px;">
                    <span class="fw-bold" style="color: #8B4513;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Ringkasan Pembayaran -->
<div class="summary-box">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-1">Total Pembayaran</h6>
            <small class="opacity-75">{{ $trx->details->sum('quantity') }} produk</small>
        </div>
        <h4 class="mb-0 fw-bold">
            Rp {{ number_format($trx->final_price, 0, ',', '.') }}
        </h4>
    </div>
</div>
@endsection