@extends('member_master')

@section('konten_member')
    <!-- Pastikan FontAwesome terpasang untuk icon kaca pembesar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="container-fluid py-4 px-4"> <!-- container-fluid agar melebar ke samping -->
        
        <!-- Header & Search Box (Mepet Kiri) -->
        <div class="row mb-4">
            <div class="col-12 text-start">
                <h4 class="fw-bold">Riwayat Transaksi</h4>
                
                <!-- Form Pencarian dengan max-width agar textbox tidak terlalu lebar ke kanan -->
                <form action="/riwayat-transaksi" method="GET" class="mt-3" style="max-width: 400px;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari No. Transaksi..." value="{{ request('search') }}">
                        <button class="btn" style="background-color: #8B4513; color: white" type="submit">
                            <i class="fas fa-search me-1"></i> Cari <!-- Icon Kaca Pembesar -->
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grid Card Transaksi -->
<div class="row">
    @forelse($transactions as $trx)
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <!-- Tambahkan class d-block dan pastikan tag </a> ditutup di bawah -->
            <a href="/riwayat-transaksi/detail/{{ $trx->id }}" class="text-decoration-none d-block h-100">
                <div class="card h-100 shadow-sm border-0"> 
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $trx->location->location_name ?? 'Lokasi' }}</h6>
                                <small class="text-muted">{{ $trx->created_at->format('d-m-Y') }}</small>
                            </div>
                            <span class="badge {{ $trx->status == 'completed' ? 'bg-light text-success' : 'bg-light text-danger' }}" style="font-size: 0.8rem;">
                                {{ $trx->status == 'completed' ? 'Sudah Diambil' : ucfirst($trx->status) }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">No Transaksi {{ $trx->invoice_number }}</small>
                        </div>

                        <div class="mb-3" style="min-height: 50px;">
                            @if($trx->details->count() > 0)
                                @php 
                                    $firstProduct = $trx->details->first()->product->product_name;
                                    $othersCount = $trx->details->count() - 1;
                                @endphp
                                <span class="fw-bold text-secondary">{{ $firstProduct }}</span>
                                @if($othersCount > 0)
                                    <span class="text-muted small"> dan {{ $othersCount }} produk lain</span>
                                @endif
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <small class="text-muted">{{ $trx->details->count() }} Produk</small>
                            <div>
                                <small class="text-muted">Total Harga</small>
                                <span class="fw-bold d-block" style="color: #8B4513; font-size: 1.1rem;">
                                    Rp {{ number_format($trx->final_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a> <!-- INI YANG PENTING: Jangan lupa tutup tag a di sini -->
        </div>
    @empty
        <div class="col-12 text-center mt-5">
            <p class="text-muted">Tidak ada transaksi ditemukan.</p>
        </div>
    @endforelse
</div>
    </div>
@endsection