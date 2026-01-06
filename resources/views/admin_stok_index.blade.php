@extends('admin_master')

@section('konten_admin')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white"><h5 class="fw-bold mb-0">Atur Stok & Harga per Lokasi</h5></div>
    <div class="card-body">
        <p>Pilih lokasi toko yang ingin diatur stok rotinya:</p>
        <div class="row">
            @foreach($locations as $loc)
            <div class="col-md-4 mb-3">
                <div class="card p-3 text-center border-0 shadow-sm bg-light">
                    <h6 class="fw-bold">{{ $loc->location_name }}</h6>
                    <p class="small text-muted">{{ $loc->address }}</p>
                    <a href="/admin/stok/kelola/{{ $loc->id }}" class="btn btn-primary btn-sm">Kelola Produk</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection