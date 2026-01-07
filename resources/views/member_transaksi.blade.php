<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Larita Bakery - Transaski</title>
</head>
<body>
    

    @extends('member_master')

    @section('konten_member')
        <div class="container py-4" style="max-width: 600px;">
            <h4 class="mb-4 fw-bold">Riwayat Transaksi</h4>

            <!-- Search Box -->
            <form action="{{ route('member.transaksi') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari No. Transaksi..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>

            <!-- List Transaksi -->
            @forelse($transactions as $trx)
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $trx->location->location_name ?? 'Lokasi' }}</h6>
                                <small class="text-muted">{{ $trx->created_at->format('d-m-Y') }}</small>
                            </div>
                            <!-- Status -->
                            <span class="badge {{ $trx->status == 'completed' ? 'bg-light text-success' : 'bg-light text-danger' }}" style="font-size: 0.8rem;">
                                {{ $trx->status == 'completed' ? 'Sudah Diambil' : ucfirst($trx->status) }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">No Transaksi {{ $trx->invoice_number }}</small>
                        </div>

                        <!-- Logika Nama Produk -->
                        <div class="mb-3">
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
                                <span class="text-muted small">Total Harga</span>
                                <span class="fw-bold text-danger ms-2">Rp {{ number_format($trx->final_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center mt-5">
                    <p class="text-muted">Tidak ada transaksi ditemukan.</p>
                </div>
            @endforelse
        </div>
    @endsection
</body>
</html>