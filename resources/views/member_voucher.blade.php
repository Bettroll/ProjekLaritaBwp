@extends('member_master')

@section('konten_member')
<div class="row">
    <!-- INFO POIN -->
    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #8B4513, #D2691E);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Poin Loyalitas Anda</h5>
                    <h2 class="fw-bold mb-0">⭐ {{ number_format($user->points) }} <small class="fs-6">Poin</small></h2>
                </div>
                <div class="text-end">
                    <p class="mb-0 small opacity-75">Tukarkan poin dengan voucher belanja menarik!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR VOUCHER -->
    <div class="col-md-12">
        <h4 class="fw-bold mb-3 text-larita">Voucher Tersedia</h4>
        
        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <div class="row">
            @forelse($vouchers as $v)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #ffc107 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold text-larita mb-0">{{ $v->voucher_name }}</h5>
                            <span class="badge bg-success">{{ $v->discount_percent }}% OFF</span>
                        </div>
                        <hr>
                        <p class="small text-muted mb-1">Syarat & Ketentuan:</p>
                        <ul class="small ps-3 text-secondary">
                            <li>Minimal belanja Rp {{ number_format($v->min_purchase) }}</li>
                            <li>Hanya bisa ditukar 1 kali</li>
                            <li>Kuota tersisa: {{ $v->quota }}</li>
                        </ul>
                        <div class="bg-light p-2 rounded text-center mb-3">
                            <span class="text-muted small">Dibutuhkan:</span><br>
                            <span class="fw-bold text-primary">{{ number_format($v->points_needed) }} Poin</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <form action="/voucher/tukar/{{ $v->id }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 fw-bold" 
                                    {{ $user->points < $v->points_needed ? 'disabled' : '' }}>
                                {{ $user->points < $v->points_needed ? 'Poin Tidak Cukup' : 'Tukarkan Poin' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/10541/10541334.png" width="80" class="opacity-50 mb-3">
                <h5 class="text-muted">Belum ada voucher baru yang bisa ditukar.</h5>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection