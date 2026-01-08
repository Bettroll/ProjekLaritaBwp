@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Manajemen Voucher</h3>
    <p>Kelola voucher diskon terbatas untuk member</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);">
            <div class="stats-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
            <p class="mb-1">Total Voucher</p>
            <h2>{{ $vouchers->count() }}</h2>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stats-icon"><i class="bi bi-check-circle-fill"></i></div>
            <p class="mb-1">Voucher Tersedia</p>
            <h2>{{ $vouchers->where('quota', '>', 0)->count() }}</h2>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
            <div class="stats-icon"><i class="bi bi-stack"></i></div>
            <p class="mb-1">Total Kuota</p>
            <h2>{{ $vouchers->sum('quota') }}</h2>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
            <div class="stats-icon"><i class="bi bi-percent"></i></div>
            <p class="mb-1">Avg Diskon</p>
            <h2>{{ round($vouchers->avg('discount_percent'), 1) }}%</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-ticket-perforated-fill me-2"></i>Daftar Voucher
        </h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i>Buat Voucher Baru
        </button>
    </div>
    <div class="card-body">
        @if($vouchers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama Voucher</th>
                        <th class="text-center">Diskon</th>
                        <th class="text-center">Min. Belanja</th>
                        <th class="text-center">Poin</th>
                        <th class="text-center">Kuota</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vouchers as $v)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-circle me-3" style="background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);">
                                    <i class="bi bi-ticket-perforated text-white"></i>
                                </div>
                                <div>
                                    <span class="fw-bold">{{ $v->voucher_name }}</span>
                                    @if($v->quota <= 0)
                                        <span class="badge bg-secondary ms-2">Habis</span>
                                    @elseif($v->quota <= 5)
                                        <span class="badge bg-warning ms-2">Sisa {{ $v->quota }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success fs-6">{{ $v->discount_percent }}%</span>
                        </td>
                        <td class="text-center">
                            <span class="text-muted">Rp {{ number_format($v->min_purchase) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge" style="background-color: rgba(78, 115, 223, 0.1); color: #4e73df;">
                                <i class="bi bi-star-fill me-1"></i>{{ $v->points_needed }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $v->quota > 0 ? 'bg-info' : 'bg-secondary' }}">
                                {{ $v->quota }} Pcs
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $v->id }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <a href="/admin/voucher/hapus/{{ $v->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus voucher ini?')">
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
            <i class="bi bi-ticket-perforated" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="mt-3 text-muted">Belum ada voucher</h5>
            <p class="text-muted">Klik tombol "Buat Voucher Baru" untuk menambahkan voucher</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Edit - Dipindahkan ke luar tabel -->
@foreach($vouchers as $v)
<div class="modal fade" id="modalEdit{{ $v->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/voucher/update/{{ $v->id }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Voucher
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Voucher</label>
                        <input type="text" name="voucher_name" class="form-control" value="{{ $v->voucher_name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Diskon (%)</label>
                            <div class="input-group">
                                <input type="number" name="discount_percent" class="form-control" value="{{ $v->discount_percent }}" min="1" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Poin Dibutuhkan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                <input type="number" name="points_needed" class="form-control" value="{{ $v->points_needed }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Min. Belanja</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="min_purchase" class="form-control" value="{{ $v->min_purchase }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kuota</label>
                            <div class="input-group">
                                <input type="number" name="quota" class="form-control" value="{{ $v->quota }}" required>
                                <span class="input-group-text">Pcs</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Update Voucher
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
            <form action="/admin/voucher/tambah" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Buat Voucher Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Voucher</label>
                        <input type="text" name="voucher_name" class="form-control" placeholder="Contoh: Voucher Kilat 10%" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Diskon (%)</label>
                            <div class="input-group">
                                <input type="number" name="discount_percent" class="form-control" placeholder="10" min="1" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Poin Dibutuhkan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                <input type="number" name="points_needed" class="form-control" placeholder="100" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Min. Belanja</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="min_purchase" class="form-control" placeholder="50000" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kuota</label>
                            <div class="input-group">
                                <input type="number" name="quota" class="form-control" placeholder="10" required>
                                <span class="input-group-text">Pcs</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Simpan Voucher
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