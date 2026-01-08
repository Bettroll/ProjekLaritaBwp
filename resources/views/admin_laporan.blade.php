@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Laporan Penjualan</h3>
    <p>Analisis data penjualan toko Larita</p>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-cash-stack"></i></div>
            <p class="mb-1">Total Pendapatan</p>
            <h2>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            <small>Dari pesanan selesai</small>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
            <div class="stats-icon"><i class="bi bi-receipt"></i></div>
            <p class="mb-1">Total Transaksi</p>
            <h2>{{ $totalSales }}</h2>
            <small>Pesanan selesai</small>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
            <div class="stats-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <p class="mb-1">Rata-rata/Transaksi</p>
            <h2>Rp {{ $totalSales > 0 ? number_format($totalRevenue / $totalSales, 0, ',', '.') : '0' }}</h2>
            <small>Per pesanan</small>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-funnel-fill me-2"></i>Filter Laporan
        </h6>
    </div>
    <div class="card-body">
        <form action="/admin/laporan" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Lokasi Toko</label>
                <select name="location_id" class="form-select">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->location_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="/admin/laporan" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-table me-2"></i>Data Penjualan
        </h6>
        <span class="badge bg-primary">{{ $transactions->count() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3">Tanggal & Invoice</th>
                        <th>Pelanggan</th>
                        <th>Lokasi</th>
                        <th>Metode</th>
                        <th class="text-end pe-3">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
                        <td class="px-3">
                            <span class="badge bg-secondary mb-1">{{ $t->invoice_number }}</span><br>
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i>{{ $t->created_at->format('d M Y, H:i') }}
                            </small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-circle me-2" style="width: 32px; height: 32px; background: rgba(139, 69, 19, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-person-fill" style="color: #8B4513; font-size: 0.8rem;"></i>
                                </div>
                                <span>{{ $t->user->name ?? 'User Dihapus' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: rgba(54, 185, 204, 0.1); color: #36b9cc;">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $t->location->location_name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($t->shipping_method == 'pickup')
                                <span class="badge bg-success">
                                    <i class="bi bi-shop me-1"></i>Ambil Sendiri
                                </span>
                            @else
                                <span class="badge bg-primary">
                                    <i class="bi bi-truck me-1"></i>Diantar
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <span class="fw-bold text-success">
                                Rp {{ number_format($t->final_price, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                            <p class="text-muted mt-2 mb-0">Tidak ada data penjualan untuk filter ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($transactions->count() > 0)
                <tfoot>
                    <tr class="table-light">
                        <td colspan="4" class="text-end fw-bold px-3">Total Keseluruhan:</td>
                        <td class="text-end fw-bold text-success pe-3">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection