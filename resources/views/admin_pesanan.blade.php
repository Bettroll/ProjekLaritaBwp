@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Pesanan Masuk</h3>
    <p>Kelola semua pesanan dari pelanggan</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
            <div class="stats-icon"><i class="bi bi-hourglass-split"></i></div>
            <p class="mb-1">Pending</p>
            <h2>{{ $stats['pending'] }}</h2>
            <small>Menunggu konfirmasi</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
            <div class="stats-icon"><i class="bi bi-arrow-repeat"></i></div>
            <p class="mb-1">Diproses</p>
            <h2>{{ $stats['processing'] }}</h2>
            <small>Sedang diproses</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
            <div class="stats-icon"><i class="bi bi-check-circle-fill"></i></div>
            <p class="mb-1">Siap</p>
            <h2>{{ $stats['ready'] }}</h2>
            <small>Siap diambil/dikirim</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-trophy-fill"></i></div>
            <p class="mb-1">Selesai</p>
            <h2>{{ $stats['completed'] }}</h2>
            <small>Pesanan selesai</small>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-funnel-fill me-2"></i>Filter & Pencarian
        </h6>
    </div>
    <div class="card-body">
        <form action="/admin/pesanan" method="GET" class="row g-3 align-items-end">
            <!-- Search Bar -->
            <div class="col-md-5">
                <label class="form-label fw-semibold">Cari Pesanan</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari ID, Invoice, Nama, Email, Lokasi, Tanggal..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">Filter Status</label>
                <select name="status" class="form-select">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>🔄 Diproses</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>✅ Siap</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🎉 Selesai</option>
                </select>
            </div>
            
            <!-- Buttons -->
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
                <a href="/admin/pesanan" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-cart-check-fill me-2"></i>Daftar Pesanan
        </h6>
        <small class="text-muted">Total: {{ $stats['total'] }} pesanan</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3">ID</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Lokasi</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'ready' => 'primary',
                            'completed' => 'success'
                        ];
                        $statusIcons = [
                            'pending' => '⏳',
                            'processing' => '🔄',
                            'ready' => '✅',
                            'completed' => '🎉'
                        ];
                    @endphp
                    <tr>
                        <td class="px-3 fw-bold">#{{ $order->id }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->invoice_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name ?? 'User Deleted' }}</div>
                            <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $order->location->location_name ?? '-' }}</span>
                        </td>
                        <td>
                            @if($order->discount_amount > 0)
                                <small class="text-decoration-line-through text-muted d-block">Rp {{ number_format($order->total_price) }}</small>
                            @endif
                            <span class="fw-bold text-success">Rp {{ number_format($order->final_price) }}</span>
                            @if($order->discount_amount > 0)
                                <small class="text-danger d-block">-Rp {{ number_format($order->discount_amount) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($order->shipping_method == 'pickup')
                                <span class="badge bg-success">🏪 Ambil Sendiri</span>
                            @else
                                <span class="badge bg-primary">🚚 Diantar</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $order->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ $statusIcons[$order->status] ?? '' }} {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">
                                    👁️ Detail
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                    ✏️ Status
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="mb-3 opacity-50">
                            <p class="text-muted mb-0">Tidak ada pesanan ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
            </small>
            {{ $orders->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Modals (Di luar tabel) -->
@foreach($orders as $order)
@php
    $statusColors = [
        'pending' => 'warning',
        'processing' => 'info',
        'ready' => 'primary',
        'completed' => 'success'
    ];
    $statusIcons = [
        'pending' => '⏳',
        'processing' => '🔄',
        'ready' => '✅',
        'completed' => '🎉'
    ];
@endphp

<!-- Detail Modal -->
<div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">📦 Detail Pesanan {{ $order->invoice_number }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Customer Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3">👤 Informasi Pelanggan</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="120" class="text-muted">Nama</td>
                                <td class="fw-semibold">{{ $order->user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $order->user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Telepon</td>
                                <td>{{ $order->user->phone ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3">📍 Informasi Pengiriman</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="120" class="text-muted">Lokasi</td>
                                <td class="fw-semibold">{{ $order->location->location_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Metode</td>
                                <td>
                                    @if($order->shipping_method == 'pickup')
                                        <span class="badge bg-success">🏪 Ambil Sendiri</span>
                                    @else
                                        <span class="badge bg-primary">🚚 Diantar</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Detail</td>
                                <td><small>{{ $order->shipping_details ?? '-' }}</small></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Order Items -->
                <h6 class="fw-bold text-primary mb-3">🛒 Daftar Produk</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $i => $detail)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $detail->product->product_name ?? 'Produk Dihapus' }}</td>
                            <td class="text-end">Rp {{ number_format($detail->price_at_purchase) }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($detail->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-semibold">Subtotal</td>
                            <td class="text-end">Rp {{ number_format($order->total_price) }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr class="text-danger">
                            <td colspan="4" class="text-end fw-semibold">Diskon Voucher</td>
                            <td class="text-end">- Rp {{ number_format($order->discount_amount) }}</td>
                        </tr>
                        @endif
                        @if($order->shipping_method == 'delivery')
                        <tr>
                            <td colspan="4" class="text-end fw-semibold">Ongkir</td>
                            <td class="text-end">Rp 10,000</td>
                        </tr>
                        @endif
                        <tr class="table-success">
                            <td colspan="4" class="text-end fw-bold">Total Bayar</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($order->final_price) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Order Info -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <small class="text-muted">Tanggal Pesan: <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong></small>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">Poin Didapat: <strong class="text-success">+{{ $order->points_earned }} poin</strong></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">✏️ Ubah Status Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/pesanan/status/{{ $order->id }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h6 class="fw-bold">{{ $order->invoice_number }}</h6>
                        <small class="text-muted">{{ $order->user->name ?? 'User' }} - {{ $order->location->location_name ?? 'Lokasi' }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Saat Ini</label>
                        <div>
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                                {{ $statusIcons[$order->status] ?? '' }} {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ubah Status Menjadi</label>
                        <div class="d-grid gap-2">
                            <label class="btn btn-outline-warning status-btn {{ $order->status == 'pending' ? 'active' : '' }}">
                                <input type="radio" name="status" value="pending" class="d-none" {{ $order->status == 'pending' ? 'checked' : '' }}>
                                ⏳ Pending - Menunggu konfirmasi
                            </label>
                            <label class="btn btn-outline-info status-btn {{ $order->status == 'processing' ? 'active' : '' }}">
                                <input type="radio" name="status" value="processing" class="d-none" {{ $order->status == 'processing' ? 'checked' : '' }}>
                                🔄 Processing - Sedang diproses
                            </label>
                            <label class="btn btn-outline-primary status-btn {{ $order->status == 'ready' ? 'active' : '' }}">
                                <input type="radio" name="status" value="ready" class="d-none" {{ $order->status == 'ready' ? 'checked' : '' }}>
                                ✅ Ready - Siap diambil/dikirim
                            </label>
                            <label class="btn btn-outline-success status-btn {{ $order->status == 'completed' ? 'active' : '' }}">
                                <input type="radio" name="status" value="completed" class="d-none" {{ $order->status == 'completed' ? 'checked' : '' }}>
                                🎉 Completed - Pesanan selesai
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .btn-check:checked + .btn-outline-warning { background-color: #ffc107; border-color: #ffc107; color: #000; }
    .btn-check:checked + .btn-outline-info { background-color: #0dcaf0; border-color: #0dcaf0; color: #000; }
    .btn-check:checked + .btn-outline-primary { background-color: #0d6efd; border-color: #0d6efd; color: #fff; }
    .btn-check:checked + .btn-outline-success { background-color: #198754; border-color: #198754; color: #fff; }
    
    /* Active state styles */
    .status-btn.active.btn-outline-warning { background-color: #ffc107; border-color: #ffc107; color: #000; }
    .status-btn.active.btn-outline-info { background-color: #0dcaf0; border-color: #0dcaf0; color: #000; }
    .status-btn.active.btn-outline-primary { background-color: #0d6efd; border-color: #0d6efd; color: #fff; }
    .status-btn.active.btn-outline-success { background-color: #198754; border-color: #198754; color: #fff; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status radio button changes
    document.querySelectorAll('.status-btn').forEach(function(label) {
        label.addEventListener('click', function() {
            // Get the parent container
            const container = this.closest('.d-grid');
            
            // Remove active class from all buttons in this container
            container.querySelectorAll('.status-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Check the radio input
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        });
    });
});
</script>

@endsection
