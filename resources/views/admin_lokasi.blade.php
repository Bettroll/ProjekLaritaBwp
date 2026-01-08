@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Master Lokasi</h3>
    <p>Kelola cabang toko Larita</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-check-circle-fill"></i></div>
            <p class="mb-1">Lokasi Aktif</p>
            <h2>{{ $data->where('is_active', 1)->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);">
            <div class="stats-icon"><i class="bi bi-x-circle-fill"></i></div>
            <p class="mb-1">Lokasi Non-Aktif</p>
            <h2>{{ $data->where('is_active', 0)->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
            <div class="stats-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <p class="mb-1">Total Lokasi</p>
            <h2>{{ $data->count() }}</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-geo-alt-fill me-2"></i>Daftar Lokasi Toko
        </h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i>Tambah Lokasi
        </button>
    </div>
    <div class="card-body">
        @if($data->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Lokasi</th>
                        <th>Alamat</th>
                        <th width="100" class="text-center">Status</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $item)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark">{{ $key + 1 }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-circle me-3" style="background: {{ $item->is_active ? 'rgba(28, 200, 138, 0.1)' : 'rgba(231, 74, 59, 0.1)' }};">
                                    <i class="bi bi-shop" style="color: {{ $item->is_active ? '#1cc88a' : '#e74a3b' }};"></i>
                                </div>
                                <span class="fw-semibold">{{ $item->location_name }}</span>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="bi bi-pin-map me-1"></i>{{ $item->address }}
                            </small>
                        </td>
                        <td class="text-center">
                            @if($item->is_active)
                                <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i>Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <a href="/admin/lokasi/hapus/{{ $item->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus lokasi ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-geo-alt-fill" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="mt-3 text-muted">Belum ada lokasi terdaftar</h5>
            <p class="text-muted">Klik tombol "Tambah Lokasi" untuk menambahkan cabang baru</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Edit - Dipindahkan ke luar tabel -->
@foreach($data as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/lokasi/update/{{ $item->id }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Lokasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lokasi</label>
                        <input type="text" name="location_name" class="form-control" value="{{ $item->location_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="3" required>{{ $item->address }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Lokasi</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ $item->is_active == 1 ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="0" {{ $item->is_active == 0 ? 'selected' : '' }}>❌ Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Update Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/lokasi/tambah" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Lokasi Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lokasi / Cabang</label>
                        <input type="text" name="location_name" class="form-control" placeholder="Contoh: Larita Surabaya Barat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" placeholder="Alamat lengkap cabang" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection