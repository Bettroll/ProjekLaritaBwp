@extends('admin_master')

@section('konten_admin')
<div class="container-fluid">
    <h4 class="fw-bold mb-4">📊 Laporan Penjualan Larita</h4>

    <!-- Bagian Filter (Kriteria: Minimal 1 + Filter) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="/admin/laporan" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Lokasi Toko</label>
                    <select name="location_id" class="form-select">
                        <option value="">Semua Lokasi</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->location_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="/admin/laporan" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body p-4 text-center">
                    <h6 class="text-uppercase small opacity-75">Total Pendapatan (Final)</h6>
                    <h2 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h6 class="text-uppercase small opacity-75">Total Pesanan Selesai</h6>
                    <h2 class="fw-bold mb-0">{{ $totalSales }} Transaksi</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Tgl & Invoice</th>
                            <th>Pelanggan</th>
                            <th>Lokasi</th>
                            <th>Metode</th>
                            <th class="text-end">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $t->invoice_number }}</span><br>
                                <small class="text-muted">{{ $t->created_at->format('d M Y, H:i') }}</small>
                            </td>
                            <td>{{ $t->user->name ?? 'User Dihapus' }}</td>
                            <td>{{ $t->location->location_name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $t->shipping_method == 'pickup' ? 'Ambil Sendiri' : 'Antar Alamat' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($t->final_price, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Tidak ada data penjualan untuk filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection