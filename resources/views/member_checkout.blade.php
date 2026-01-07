@extends('member_master')

@section('konten_member')
<!-- 1. Bungkus seluruh halaman dengan FORM -->
<form action="/checkout/proses" method="POST">
    @csrf <!-- WAJIB: Keamanan Laravel -->

    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold mb-4">Konfirmasi Pesanan</h4>
            
            <!-- 2. Kirim kembali ID Keranjang yang dipilih sebagai input hidden -->
            @foreach($cartItems as $item)
                <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
            @endforeach

            <!-- METODE PENGAMBILAN -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Pilih Metode Pengambilan</div>
                <div class="card-body">
                    <div class="d-flex gap-3 mb-4">
                        <input type="radio" class="btn-check" name="shipping_method" id="method_pickup" value="pickup" checked>
                        <label class="btn btn-outline-primary px-4" for="method_pickup">🚶 Ambil Sendiri</label>

                        <input type="radio" class="btn-check" name="shipping_method" id="method_delivery" value="delivery">
                        <label class="btn btn-outline-primary px-4" for="method_delivery">🚚 Diantar ke Alamat</label>
                    </div>

                    <!-- Form Ambil Sendiri -->
                    <div id="form_pickup">
                        <div class="alert alert-light border small mb-3">
                            <strong>Lokasi Pengambilan:</strong> {{ $location->location_name }}<br>
                            {{ $location->address }}
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Nama Pengambil</label>
                                <input type="text" name="pickup_name" class="form-control" value="{{ Auth::user()->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Estimasi Jam Ambil</label>
                                <input type="time" name="pickup_time" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Form Diantar -->
                    <div id="form_delivery" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label small">Alamat Pengiriman</label>
                            <textarea name="delivery_address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Nama Penerima</label>
                                <input type="text" name="receiver_name" class="form-control" value="{{ Auth::user()->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Waktu Tiba (Estimasi)</label>
                                <input type="datetime-local" name="delivery_time" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAIL PESANAN -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Detail Produk</div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach($cartItems as $item)
                                @php 
                                    $price = $item->product->locations->where('id', session('selected_location_id'))->first()->pivot->price;
                                    $itemTotal = $price * $item->quantity;
                                    $subtotal += $itemTotal;
                                @endphp
                                <tr>
                                    <td width="80" class="ps-3">
                                        <img src="{{ asset('images/products/'.$item->product->image) }}" width="60" class="rounded shadow-sm">
                                    </td>
                                    <td>
                                        <h6 class="mb-0 fw-bold">{{ $item->product->product_name }}</h6>
                                        <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($price) }}</small>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="fw-bold">Rp {{ number_format($itemTotal) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PILIH VOUCHER -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Pilih Voucher Diskon</div>
                <div class="card-body">
                    <!-- Tambahkan name="voucher_id" agar data terkirim -->
                    <select id="select_voucher" name="voucher_id" class="form-select">
                        <option value="" data-discount="0" data-min="0">-- Gunakan Voucher --</option>
                        @foreach($userVouchers as $v)
                            <option value="{{ $v->id }}" data-discount="{{ $v->discount_percent }}" data-min="{{ $v->min_purchase }}">
                                {{ $v->voucher_name }} (Disc. {{ $v->discount_percent }}%) - Min. Rp {{ number_format($v->min_purchase) }}
                            </option>
                        @endforeach
                    </select>
                    <div id="voucher_msg" class="text-danger small mt-2"></div>
                </div>
            </div>
        </div>

        <!-- RINGKASAN PEMBAYARAN -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-white fw-bold">Ringkasan Belanja</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal Produk</span>
                        <span id="display_subtotal" data-value="{{ $subtotal }}">Rp {{ number_format($subtotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Pengiriman</span>
                        <span id="display_shipping" data-value="0">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Potongan Voucher</span>
                        <span id="display_discount" class="text-success" data-value="0">Rp 0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="fw-bold">Total Tagihan</h5>
                        <h5 class="fw-bold text-success" id="display_total">Rp {{ number_format($subtotal) }}</h5>
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100 btn-lg fw-bold shadow-sm">
                        Proses Pesanan Sekarang
                    </button>
                    
                    <a href="/keranjang" class="btn btn-link w-100 text-muted mt-2 text-decoration-none small">
                        Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let rawSubtotal = parseInt($('#display_subtotal').attr('data-value'));
    let shippingCost = 0;
    let discountAmount = 0;

    function updateTotal() {
        let finalTotal = rawSubtotal + shippingCost - discountAmount;
        $('#display_total').text('Rp ' + finalTotal.toLocaleString('id-ID'));
    }

    $('input[name="shipping_method"]').change(function() {
        if ($(this).val() == 'delivery') {
            $('#form_delivery').slideDown();
            $('#form_pickup').hide();
            shippingCost = 10000;
            $('#display_shipping').text('Rp 10.000');
        } else {
            $('#form_delivery').hide();
            $('#form_pickup').slideDown();
            shippingCost = 0;
            $('#display_shipping').text('Rp 0');
        }
        updateTotal();
    });

    $('#select_voucher').change(function() {
        let option = $(this).find('option:selected');
        let minPurchase = parseInt(option.data('min'));
        let discPercent = parseInt(option.data('discount'));

        if (rawSubtotal < minPurchase) {
            $('#voucher_msg').text('Belanjaan belum cukup (Min. Rp ' + minPurchase.toLocaleString('id-ID') + ')');
            $(this).val(""); 
            discountAmount = 0;
        } else {
            $('#voucher_msg').text('');
            discountAmount = (rawSubtotal * discPercent) / 100;
        }

        $('#display_discount').text('- Rp ' + discountAmount.toLocaleString('id-ID'));
        updateTotal();
    });
});
</script>
@endsection