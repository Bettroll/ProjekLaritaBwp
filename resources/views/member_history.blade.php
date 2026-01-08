@extends('member_master')

@section('konten_member')
<div class="page-header">
    <h3><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi</h3>
    <p>Lihat semua pesanan yang pernah Anda lakukan</p>
</div>

<!-- Search Box -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="/riwayat-transaksi" method="GET">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" 
                       placeholder="Cari No. Transaksi..." value="{{ request('search') }}">
                <button class="btn btn-larita" type="submit">
                    Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Grid Card Transaksi -->
<div class="row">
    @forelse($transactions as $trx)
        @php
            $statusColors = [
                'pending' => 'warning',
                'processing' => 'info',
                'ready' => 'primary',
                'completed' => 'success'
            ];
            $statusIcons = [
                'pending' => 'hourglass-split',
                'processing' => 'arrow-repeat',
                'ready' => 'bag-check',
                'completed' => 'check-circle-fill'
            ];
            $statusTexts = [
                'pending' => 'Menunggu',
                'processing' => 'Diproses',
                'ready' => 'Siap Ambil',
                'completed' => 'Selesai'
            ];
        @endphp
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/riwayat-transaksi/detail/{{ $trx->id }}" class="text-decoration-none d-block h-100">
                <div class="card h-100 transaction-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $trx->location->location_name ?? 'Lokasi' }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $trx->created_at->format('d M Y') }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $statusColors[$trx->status] ?? 'secondary' }}">
                                <i class="bi bi-{{ $statusIcons[$trx->status] ?? 'question' }} me-1"></i>
                                {{ $statusTexts[$trx->status] ?? ucfirst($trx->status) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">No. Transaksi</small>
                            <span class="badge bg-light text-dark fw-normal">{{ $trx->invoice_number }}</span>
                        </div>

                        <div class="mb-3 product-preview">
                            @if($trx->details->count() > 0)
                                @php 
                                    $firstProduct = $trx->details->first()->product->product_name;
                                    $othersCount = $trx->details->count() - 1;
                                @endphp
                                <span class="fw-semibold text-dark">{{ $firstProduct }}</span>
                                @if($othersCount > 0)
                                    <span class="text-muted small"> +{{ $othersCount }} produk lain</span>
                                @endif
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="text-muted small">
                                <i class="bi bi-box-seam me-1"></i>{{ $trx->details->count() }} Produk
                            </span>
                            <div class="text-end">
                                <small class="text-muted d-block">Total</small>
                                <span class="fw-bold" style="color: #8B4513; font-size: 1.1rem;">
                                    Rp {{ number_format($trx->final_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center py-2">
                        <small class="text-primary fw-semibold">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </small>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="card text-center py-5">
                <i class="bi bi-receipt" style="font-size: 4rem; color: #ddd;"></i>
                <h5 class="mt-3 text-muted">Belum Ada Transaksi</h5>
                <p class="text-muted">Anda belum pernah melakukan transaksi.</p>
                <a href="/home" class="btn btn-larita mx-auto" style="width: fit-content;">
                    <i class="bi bi-shop me-1"></i>Mulai Belanja
                </a>
            </div>
        </div>
    @endforelse
</div>

<style>
.transaction-card {
    transition: all 0.3s ease;
    border: 1px solid #eee;
}

.transaction-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: #8B4513;
}

.transaction-card .card-footer {
    border-top: 1px solid #eee;
}

.product-preview {
    min-height: 24px;
}
</style>
@endsection