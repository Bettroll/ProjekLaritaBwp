@extends('member_master')

@section('konten_member')
<div class="page-header">
    <h3><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h3>
    <p>Kelola pesanan Anda sebelum checkout</p>
</div>

<div class="card">
    <div class="card-body p-4">
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form action="/checkout" method="GET"> 
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="bg-light">
                            <th width="50"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th width="150">Jumlah</th>
                            <th>Total</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cartItems as $item)
                            @php
                                $locationData = $item->product->locations->where('id', $item->location_id)->first()->pivot;
                            @endphp
                            <tr class="cart-row" data-id="{{ $item->id }}" data-price="{{ $locationData->price }}">
                                <td>
                                    <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" class="form-check-input item-checkbox">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/products/'.$item->product->image) }}" 
                                             class="rounded shadow-sm me-3" 
                                             style="width: 70px; height: 70px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $item->product->product_name }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-box-seam me-1"></i>Stok: {{ $locationData->stock }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold" style="color: #8B4513;">
                                        Rp {{ number_format($locationData->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary btn-minus">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control text-center qty-input" 
                                               value="{{ $item->quantity }}" min="1" max="{{ $locationData->stock }}" readonly>
                                        <button type="button" class="btn btn-outline-secondary btn-plus">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="fw-bold row-total text-success">
                                    Rp {{ number_format($locationData->price * $item->quantity, 0, ',', '.') }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-modal" 
                                            data-id="{{ $item->id }}" 
                                            data-name="{{ $item->product->product_name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-cart-x" style="font-size: 4rem; color: #ddd;"></i>
                                    <h5 class="mt-3 text-muted">Keranjang Kosong</h5>
                                    <p class="text-muted">Yuk belanja roti dulu!</p>
                                    <a href="/home" class="btn btn-larita">
                                        <i class="bi bi-shop me-1"></i>Mulai Belanja
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($cartItems->count() > 0)
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="/home" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>Tambah Pesanan Lain
                </a>
                <div class="text-end">
                    <p class="text-muted mb-1 small">Subtotal Terpilih:</p>
                    <h3 class="fw-bold text-success mb-3" id="subtotalDisplay">Rp 0</h3>
                    <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold shadow-sm" id="btnCheckout" disabled>
                        <i class="bi bi-credit-card me-1"></i>Lanjut ke Checkout
                    </button>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-body text-center py-5">
                <i class="bi bi-trash text-danger" style="font-size: 4rem;"></i>
                <h5 class="mt-3 fw-bold">Hapus dari Keranjang?</h5>
                <p class="text-muted">Apakah Anda yakin ingin menghapus<br><strong id="namaProdukHapus"></strong>?</p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <a href="#" id="linkHapusFix" class="btn btn-danger px-4">
                        <i class="bi bi-trash me-1"></i>Ya, Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Peringatan Stok Tidak Mencukupi -->
<div class="modal fade" id="modalStokKurang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-body text-center py-5">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <h5 class="mt-3 fw-bold">Stok Tidak Cukup</h5>
                <p class="text-muted">Maaf, jumlah pesanan Anda<br><strong>melebihi stok yang tersedia</strong>.</p>
                <button type="button" class="btn btn-warning px-5 mt-3" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-1"></i>Oke, Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    // 1. Fungsi Hitung Subtotal Dinamis
    function calculateSubtotal() {
        let subtotal = 0;
        let checkedAny = false;
        
        $('.item-checkbox:checked').each(function() {
            let row = $(this).closest('.cart-row');
            let price = parseInt(row.data('price'));
            let qty = parseInt(row.find('.qty-input').val());
            subtotal += (price * qty);
            checkedAny = true;
        });

        $('#subtotalDisplay').text('Rp ' + subtotal.toLocaleString('id-ID'));
        $('#btnCheckout').prop('disabled', !checkedAny);
    }

    // 2. Tombol Plus/Minus (AJAX)
    $('.btn-plus, .btn-minus').on('click', function(e) {
        e.preventDefault();
        let row = $(this).closest('.cart-row');
        let input = row.find('.qty-input');
        let currentQty = parseInt(input.val());
        let maxStock = parseInt(input.attr('max'));
        let cartId = row.data('id');
        
        if ($(this).hasClass('btn-plus')) {
            if (currentQty >= maxStock) {
                var modalStok = new bootstrap.Modal(document.getElementById('modalStokKurang'));
                modalStok.show();
                return;
            }
            currentQty++;
        } else if ($(this).hasClass('btn-minus')) {
            if (currentQty > 1) currentQty--;
        }

        // Jalankan Update via AJAX (Poin 15)
        $.post('/keranjang/update-qty', {
            _token: '{{ csrf_token() }}',
            cart_id: cartId,
            quantity: currentQty
        }, function(res) {
            if(res.success) {
                input.val(currentQty);
                let price = row.data('price');
                row.find('.row-total').text('Rp ' + (price * currentQty).toLocaleString('id-ID'));
                calculateSubtotal(); // Update subtotal tiap ganti qty
            }
        });
    });

    // 3. Logika Checkbox
    $('#checkAll').change(function() {
        $('.item-checkbox').prop('checked', $(this).prop('checked'));
        calculateSubtotal();
    });

    $('.item-checkbox').change(function() { 
        calculateSubtotal(); 
    });

    // 4. Logika Modal Hapus
    $('.btn-hapus-modal').on('click', function() {
        let id = $(this).data('id');
        let nama = $(this).data('name');
        $('#namaProdukHapus').text(nama);
        $('#linkHapusFix').attr('href', '/keranjang/hapus/' + id);
        var modalHapus = new bootstrap.Modal(document.getElementById('modalKonfirmasiHapus'));
        modalHapus.show();
    });
});
</script>
@endsection