@extends('admin_master')

@section('konten_admin')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Manajemen Voucher Terbatas</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Buat Voucher Baru</button>
    </div>
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nama Voucher</th>
                    <th>Diskon</th>
                    <th>Min. Belanja</th>
                    <th>Poin Dibutuhkan</th>
                    <th>Sisa Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $v)
                <tr>
                    <td><strong>{{ $v->voucher_name }}</strong></td>
                    <td><span class="badge bg-success">{{ $v->discount_percent }}%</span></td>
                    <td>Rp {{ number_format($v->min_purchase) }}</td>
                    <td><span class="text-primary fw-bold">{{ $v->points_needed }} Poin</span></td>
                    <td><span class="badge bg-info text-dark">{{ $v->quota }} Pcs</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $v->id }}">Edit</button>
                        <a href="/admin/voucher/hapus/{{ $v->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="modalEdit{{ $v->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="/admin/voucher/update/{{ $v->id }}" method="POST" class="modal-content">
                                    @csrf
                                    <div class="modal-header"><h5 style="color:#000">Edit Voucher</h5></div>
                                    <div class="modal-body text-start" style="color:#000">
                                        <div class="mb-3">
                                            <label>Nama Voucher</label>
                                            <input type="text" name="voucher_name" class="form-control" value="{{ $v->voucher_name }}" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Diskon (%)</label>
                                                <input type="number" name="discount_percent" class="form-control" value="{{ $v->discount_percent }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Poin Dibutuhkan</label>
                                                <input type="number" name="points_needed" class="form-control" value="{{ $v->points_needed }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Min. Belanja (Rp)</label>
                                                <input type="number" name="min_purchase" class="form-control" value="{{ $v->min_purchase }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Kuota Maksimal</label>
                                                <input type="number" name="quota" class="form-control" value="{{ $v->quota }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update Voucher</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/admin/voucher/tambah" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5>Terbitkan Voucher Terbatas</h5></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Voucher</label>
                    <input type="text" name="voucher_name" class="form-control" placeholder="Contoh: Voucher Kilat 10%" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Diskon (%)</label>
                        <input type="number" name="discount_percent" class="form-control" placeholder="10" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Poin Dibutuhkan</label>
                        <input type="number" name="points_needed" class="form-control" placeholder="100" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Minimal Belanja (Rp)</label>
                        <input type="number" name="min_purchase" class="form-control" placeholder="50000" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Kuota (Kuantitas)</label>
                        <input type="number" name="quota" class="form-control" placeholder="10" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Voucher</button>
            </div>
        </form>
    </div>
</div>
@endsection