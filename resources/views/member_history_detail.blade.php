@extends('member_master')

@section('konten_member')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Mengatur agar konten lebih ke atas */
    .content-container { margin-top: -20px; }

    .stepper-wrapper { 
        display: flex; 
        justify-content: space-between; 
        position: relative; 
        margin-bottom: 30px; 
        z-index: 1; /* Layer dasar */
    }

    .stepper-item { 
        position: relative; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        flex: 1; 
    }

    /* Garis Penghubung (Dibuat ke belakang) */
    .stepper-item::before { 
        position: absolute; 
        content: ""; 
        border-bottom: 3px solid #e0e0e0; /* Garis default */
        width: 100%; 
        top: 25px; /* Pas di tengah lingkaran ikon */
        left: -50%; 
        z-index: 1; /* Di bawah lingkaran ikon */
    }

    .stepper-item:first-child::before { content: none; }

    /* Lingkaran Ikon */
    .step-counter { 
        position: relative; /* Wajib ada untuk z-index */
        z-index: 2; /* Di atas garis */
        width: 50px; 
        height: 50px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 50%; 
        background-color: #e0e0e0; /* Warna default */
        color: white; 
        margin-bottom: 6px; 
        font-size: 20px; 
        transition: 0.3s;
        border: 3px solid white; /* Memberi jarak agar garis tidak menempel */
    }

    /* Status Aktif (Warna Coklat Larita) */
    .stepper-item.active .step-counter { 
        background-color: #8B4513; 
        box-shadow: 0 0 0 3px white; /* Efek memotong garis */
    }

    .stepper-item.active::before { 
        border-color: #8B4513; /* Garis jadi coklat saat aktif */
    }

    .stepper-item.active .step-name { 
        color: #8B4513; 
        font-weight: bold; 
    }
</style>

<div class="container pt-2 content-container"> <!-- Menggunakan pt-2 agar lebih ke atas -->
    <div class="row justify-content-center">
        <div class="col-md-9">
            <!-- Tombol Kembali -->
            <a href="/riwayat-transaksi" class="btn btn-link text-decoration-none p-0 mb-3" style="color: #8B4513;">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <!-- Progress Status (Stepper) -->
                    @php
                        $statuses = ['pending', 'processing', 'ready', 'completed'];
                        $currentIdx = array_search($trx->status, $statuses);
                        $icons = ['fa-hourglass-half', 'fa-gears', 'fa-bag-shopping', 'fa-circle-check'];
                        $labels = ['Pending', 'Diproses', 'Siap Ambil', 'Selesai'];
                    @endphp

                    <div class="stepper-wrapper mt-2">
                        @foreach($statuses as $index => $statusName)
                            <div class="stepper-item {{ $index <= $currentIdx ? 'active' : '' }}">
                                <div class="step-counter">
                                    <i class="fas {{ $icons[$index] }}"></i>
                                </div>
                                <div class="step-name small">{{ $labels[$index] }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mb-5">
                        <h5 class="fw-bold mt-2" style="color: #8B4513;">
                            {{ $trx->status == 'completed' ? 'Pesanan Sudah Diambil' : 'Status Pesanan: '.ucfirst($trx->status) }}
                        </h5>
                    </div>

                    <hr class="text-muted opacity-25">

                    <!-- Bagian Informasi Atas -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted small fw-bold text-uppercase">Informasi Pesanan</h6>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Nomor Invoice</span>
                                <span class="fw-bold">{{ $trx->invoice_number }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Tanggal Transaksi</span>
                                <span class="fw-bold">{{ $trx->created_at->format('d-m-Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Status Transaksi</span>
                                <span class="badge bg-light text-dark">{{ ucfirst($trx->status) }}</span>
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="col-md-6 border-start">
                            <h6 class="text-muted small fw-bold text-uppercase ps-3">Ambil Pesanan di</h6>
                            <div class="ps-3 pt-2">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-store-alt text-danger me-2"></i>
                                    <span class="fw-bold">{{ $trx->location->location_name }}</span>
                                </div>
                                <p class="text-muted small mb-1">{{ $trx->location->address }}</p>
                                <div class="bg-light p-2 rounded mt-2">
                                    <small class="d-block text-muted">Info Pengambil:</small>
                                    <small class="fw-bold">{{ $trx->shipping_details ?? 'Ambil di Toko' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pesanan (List Produk) -->
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Detail Pesanan ({{ $trx->details->count() }} produk)</h6>
                    <table class="table table-borderless">
                        <tbody>
                            @foreach($trx->details as $item)
                            <tr class="align-middle">
                                <td style="width: 80px;">
                                <!-- Menampilkan Gambar Produk dari folder public -->
                                <div class="bg-light rounded overflow-hidden" style="width: 70px; height: 70px;">
                                    @if($item->product->image)
                                        <img src="{{ asset('images/products/' . $item->product->image) }}" 
                                             alt="{{ $item->product->product_name }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <!-- Fallback jika gambar tidak ada di database -->
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-secondary text-white">
                                            <i class="fas fa-bread-slice"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <h6 class="mb-0 fw-bold text-dark">{{ $item->product->product_name }}</h6>
                                <small class="text-muted">Harga Satuan: Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</small>
                            </td>
                            <td class="text-center fw-bold" style="color: #666;">
                                {{ $item->quantity }} <small>pcs</small>
                            </td>
                            <td class="text-end fw-bold" style="color: #8B4513;">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                    <!-- Ringkasan Pembayaran -->
                    <div class="mt-4 p-3 rounded" style="background-color: #fcfcfc; border: 1px dashed #ddd;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold h6 mb-0">Total Pesanan ({{ $trx->details->sum('quantity') }} Produk)</span>
                            <span class="h5 mb-0 fw-bold" style="color: #8B4513;">
                                Rp {{ number_format($trx->final_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection