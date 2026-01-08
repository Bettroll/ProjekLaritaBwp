@extends('member_master')

@section('konten_member')
<div class="page-header">
    <h3><i class="bi bi-ticket-perforated-fill me-2"></i>Voucher & Poin</h3>
    <p>Tukarkan poin Anda dengan voucher diskon menarik</p>
</div>

<!-- INFO POIN -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="stats-card" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 opacity-75">Poin Loyalitas Anda</p>
                    <h2 class="mb-0">
                        <i class="bi bi-star-fill me-2"></i>{{ number_format($user->points) }} <small class="fs-6">Poin</small>
                    </h2>
                </div>
                <div class="text-end d-none d-md-block">
                    <p class="mb-0 small opacity-75">Tukarkan poin dengan voucher<br>belanja menarik!</p>
                </div>
            </div>
            <div class="stats-icon">
                <i class="bi bi-gift"></i>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
@endif

<!-- DAFTAR VOUCHER -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h5 class="fw-bold mb-0" style="color: #8B4513;">
        <i class="bi bi-collection me-2"></i>Voucher Tersedia
    </h5>
    <span class="badge bg-light text-dark">{{ $vouchers->count() }} voucher</span>
</div>

<div class="row">
    @forelse($vouchers as $v)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 voucher-card {{ $user->points < $v->points_needed ? 'voucher-disabled' : '' }}">
            <div class="voucher-ribbon">
                <span>{{ $v->discount_percent }}% OFF</span>
            </div>
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #8B4513;">{{ $v->voucher_name }}</h5>
                
                <div class="voucher-details mb-3">
                    <div class="detail-item">
                        <i class="bi bi-cart-check text-success"></i>
                        <span>Min. belanja Rp {{ number_format($v->min_purchase) }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-ticket text-info"></i>
                        <span>Kuota tersisa: {{ $v->quota }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-check-circle text-primary"></i>
                        <span>Hanya bisa ditukar 1 kali</span>
                    </div>
                </div>
                
                <div class="voucher-points text-center p-3 rounded mb-3">
                    <small class="text-muted d-block">Poin Dibutuhkan</small>
                    <span class="fw-bold fs-4" style="color: #8B4513;">
                        <i class="bi bi-star-fill me-1"></i>{{ number_format($v->points_needed) }}
                    </span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-4 pt-0">
                <form action="/voucher/tukar/{{ $v->id }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $user->points < $v->points_needed ? 'btn-secondary' : 'btn-warning' }} w-100 fw-bold" 
                            {{ $user->points < $v->points_needed ? 'disabled' : '' }}>
                        @if($user->points < $v->points_needed)
                            <i class="bi bi-lock-fill me-1"></i>Poin Tidak Cukup
                        @else
                            <i class="bi bi-gift me-1"></i>Tukarkan Poin
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card text-center py-5">
            <i class="bi bi-ticket-perforated" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="mt-3 text-muted">Belum Ada Voucher</h5>
            <p class="text-muted">Voucher baru akan segera tersedia. Terus kumpulkan poin Anda!</p>
        </div>
    </div>
    @endforelse
</div>

<style>
.voucher-card {
    position: relative;
    overflow: hidden;
    border: 2px solid #f0e6d3;
    transition: all 0.3s ease;
}

.voucher-card:hover:not(.voucher-disabled) {
    border-color: #8B4513;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(139, 69, 19, 0.15);
}

.voucher-disabled {
    opacity: 0.7;
}

.voucher-ribbon {
    position: absolute;
    top: 15px;
    right: -35px;
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    color: white;
    padding: 5px 40px;
    transform: rotate(45deg);
    font-weight: bold;
    font-size: 0.8rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.voucher-details {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 12px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 5px 0;
    font-size: 0.9rem;
}

.detail-item i {
    font-size: 1rem;
}

.voucher-points {
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.05) 0%, rgba(210, 105, 30, 0.1) 100%);
    border: 2px dashed #8B4513;
}
</style>
@endsection