@extends('admin_master')

@section('konten_admin')
<div class="page-header">
    <h3>Master Kategori</h3>
    <p>Kelola kategori produk roti Larita</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold" style="color: #8B4513;">
            <i class="bi bi-folder-fill me-2"></i>Daftar Kategori
        </h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
        </button>
    </div>
    <div class="card-body">
        @if($data->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Kategori</th>
                        <th width="200" class="text-center">Aksi</th>
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
                                <div class="icon-circle me-3" style="background: rgba(139, 69, 19, 0.1);">
                                    <i class="bi bi-tag-fill" style="color: #8B4513;"></i>
                                </div>
                                <span class="fw-semibold">{{ $item->category_name }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                </button>
                                <a href="/admin/kategori/hapus/{{ $item->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                    <i class="bi bi-trash-fill me-1"></i>Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-folder-x" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="mt-3 text-muted">Belum ada kategori</h5>
            <p class="text-muted">Klik tombol "Tambah Kategori" untuk menambahkan kategori baru</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Edit - Dipindahkan ke luar tabel -->
@foreach($data as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/kategori/update/{{ $item->id }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control" value="{{ $item->category_name }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
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
            <form action="/admin/kategori/tambah" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Kategori Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control" placeholder="Contoh: Roti Manis" required>
                        <small class="text-muted">Masukkan nama kategori produk roti</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Simpan
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