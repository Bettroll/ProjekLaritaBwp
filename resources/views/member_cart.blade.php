@extends('member_master')

@section('konten_member')
<div class="row">
    <div class="col-md-12">
        <h4 class="fw-bold mb-4">🛒 Keranjang Belanja Anda</h4>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Form untuk Checkout -->
                <form action="/checkout" method="GET"> 
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th width="50"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th width="150">Jumlah</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cartItems as $item)
                                    @php
                                        // Ambil data harga dan stok dari tabel pivot lokasi
                                        $locationData = $item->product->locations->where('id', $item->location_id)->first()->pivot;
                                    @endphp
                                    <tr class="cart-row" data-id="{{ $item->id }}" data-price="{{ $locationData->price }}">
                                        <td>
                                            <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" class="form-check-input item-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/products/'.$item->product->image) }}" width="60" class="rounded me-3 shadow-sm">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $item->product->product_name }}</h6>
                                                    <small class="text-muted">Stok: {{ $locationData->stock }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($locationData->price, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary btn-minus">-</button>
                                                <input type="number" class="form-control text-center qty-input" value="{{ $item->quantity }}" min="1" max="{{ $locationData->stock }}" readonly>
                                                <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
                                            </div>
                                        </td>
                                        <td class="fw-bold row-total text-success">
                                            Rp {{ number_format($locationData->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-modal" 
                                                    data-id="{{ $item->id }}" 
                                                    data-name="{{ $item->product->product_name }}">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329073.png" width="80" class="mb-3 opacity-50">
                                            <p class="text-muted">Keranjang masih kosong nih. Yuk belanja roti dulu!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="/home" class="btn btn-outline-primary">← Tambah Pesanan Lain</a>
                        <div class="text-end">
                            <h5 class="mb-0 text-muted small">Subtotal Terpilih:</h5>
                            <h3 class="fw-bold text-success mb-3" id="subtotalDisplay">Rp 0</h3>
                            
                            <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold shadow-sm" id="btnCheckout" disabled>
                                Lanjut ke Checkout
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3221/3221803.png" width="80" class="mb-3">
                <p class="mb-0">Apakah Anda yakin ingin menghapus <br><strong id="namaProdukHapus"></strong> dari keranjang?</p>
            </div>
            <div class="modal-footer bg-light justify-content-center border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="linkHapusFix" class="btn btn-danger px-4">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Peringatan Stok Tidak Mencukupi -->
<div class="modal fade" id="modalStokKurang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title fw-bold">⚠️ Perhatian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <img src="https://cdn-icons-png.flaticon.com/512/595/595067.png" width="60" class="mb-3">
                <p class="mb-0">Maaf, jumlah pesanan Anda <br><strong>melebihi stok yang tersedia</strong>.</p>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-warning w-100 fw-bold" data-bs-dismiss="modal">Oke, Mengerti</button>
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