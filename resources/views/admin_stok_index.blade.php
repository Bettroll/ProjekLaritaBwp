@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Kelola Stok</h3>
    <p>Atur stok & harga produk per lokasi</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <p class="mb-1">Lokasi Aktif</p>
            <h2>{{ $locations->where('is_active', 1)->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
            <div class="stats-icon"><i class="bi bi-shop"></i></div>
            <p class="mb-1">Total Cabang</p>
            <h2>{{ $locations->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
            <div class="stats-icon"><i class="bi bi-stack"></i></div>
            <p class="mb-1">Produk Tersedia</p>
            <h2>{{ \App\Models\Product::count() }}</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-geo-alt-fill me-2"></i>Pilih Lokasi untuk Kelola Stok
        </h6>
    </div>
    <div class="card-body">
        @if($locations->count() > 0)
        <div class="row">
            @foreach($locations as $loc)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card h-100 location-card {{ $loc->is_active ? '' : 'opacity-50' }}">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle mx-auto mb-3" style="width: 60px; height: 60px; background: {{ $loc->is_active ? 'linear-gradient(135deg, #8B4513 0%, #5D2E0D 100%)' : '#6c757d' }};">
                            <i class="bi bi-shop text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $loc->location_name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-pin-map me-1"></i>{{ Str::limit($loc->address, 50) }}
                        </p>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            @if($loc->is_active)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Non-Aktif</span>
                            @endif
                        </div>
                        <a href="/admin/stok/kelola/{{ $loc->id }}" class="btn btn-primary w-100 {{ $loc->is_active ? '' : 'disabled' }}">
                            <i class="bi bi-box-seam me-1"></i>Kelola Produk
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-geo-alt" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="mt-3 text-muted">Belum ada lokasi terdaftar</h5>
            <p class="text-muted">Tambahkan lokasi terlebih dahulu di menu Master Lokasi</p>
            <a href="/admin/lokasi" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Tambah Lokasi
            </a>
        </div>
        @endif
    </div>
</div>

<style>
.location-card {
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0;
}
.location-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    border-color: #8B4513;
}
.icon-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection