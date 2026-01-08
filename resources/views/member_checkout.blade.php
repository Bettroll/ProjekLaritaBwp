@extends('member_master')

@section('konten_member')
<style>
    .checkout-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .checkout-card .card-header {
        background: white;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
        border-radius: 16px 16px 0 0;
    }

    .method-option {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border: 2px solid #eee;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
    }

    .method-option:hover {
        border-color: #8B4513;
    }

    .method-option.active {
        border-color: #8B4513;
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.05) 0%, rgba(93, 46, 13, 0.05) 100%);
    }

    .method-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 15px;
    }

    .product-row {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .product-row:last-child {
        border-bottom: none;
    }

    .product-img {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
    }

    .summary-card {
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
    }

    .total-row {
        background: linear-gradient(135deg, #8B4513 0%, #5D2E0D 100%);
        margin: 0 -20px -20px;
        padding: 20px;
        border-radius: 0 0 16px 16px;
        color: white;
    }

    .btn-checkout {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        border: none;
        padding: 15px 30px;
        font-weight: 600;
        border-radius: 12px;
        color: #333;
        width: 100%;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #ddd;
    }

    .form-control:focus, .form-select:focus {
        border-color: #8B4513;
        box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.15);
    }
</style>

<div class="page-header">
    <h3><i class="bi bi-bag-check me-2"></i>Checkout</h3>
    <p>Konfirmasi dan selesaikan pesanan Anda</p>
</div>

<form action="/checkout/proses" method="POST">
    @csrf

    @foreach($cartItems as $item)
        <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
    @endforeach

    <div class="row">
        <div class="col-lg-8">
            <!-- METODE PENGAMBILAN -->
            <div class="checkout-card card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-truck me-2" style="color: #8B4513;"></i>Metode Pengambilan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex gap-3 mb-4">
                        <label class="method-option active" id="opt_pickup">
                            <input type="radio" class="d-none" name="shipping_method" value="pickup" checked>
                            <div class="method-icon bg-light">
                                <i class="bi bi-person-walking" style="color: #8B4513;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Ambil Sendiri</h6>
                                <small class="text-muted">Gratis</small>
                            </div>
                        </label>

                        <label class="method-option" id="opt_delivery">
                            <input type="radio" class="d-none" name="shipping_method" value="delivery">
                            <div class="method-icon bg-light">
                                <i class="bi bi-bicycle" style="color: #8B4513;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Diantar</h6>
                                <small class="text-muted">+Rp 10.000</small>
                            </div>
                        </label>
                    </div>

                    <!-- Form Ambil Sendiri -->
                    <div id="form_pickup">
                        <div class="alert alert-light border-0 rounded-3 mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-2 me-3" style="background: rgba(139, 69, 19, 0.1);">
                                    <i class="bi bi-shop" style="color: #8B4513;"></i>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $location->location_name }}</strong>
                                    <small class="text-muted">{{ $location->address }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">
                                    <i class="bi bi-person me-1"></i>Nama Pengambil
                                </label>
                                <input type="text" name="pickup_name" class="form-control" value="{{ Auth::user()->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">
                                    <i class="bi bi-clock me-1"></i>Estimasi Jam Ambil
                                </label>
                                <input type="time" name="pickup_time" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Form Diantar -->
                    <div id="form_delivery" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                <i class="bi bi-geo-alt me-1"></i>Alamat Pengiriman
                            </label>
                            <textarea name="delivery_address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">
                                    <i class="bi bi-person me-1"></i>Nama Penerima
                                </label>
                                <input type="text" name="receiver_name" class="form-control" value="{{ Auth::user()->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">
                                    <i class="bi bi-calendar-event me-1"></i>Waktu Tiba (Estimasi)
                                </label>
                                <input type="datetime-local" name="delivery_time" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAIL PESANAN -->
            <div class="checkout-card card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-box-seam me-2" style="color: #8B4513;"></i>Detail Produk ({{ count($cartItems) }} item)
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php $subtotal = 0; @endphp
                    @foreach($cartItems as $item)
                        @php 
                            $price = $item->product->locations->where('id', session('selected_location_id'))->first()->pivot->price;
                            $itemTotal = $price * $item->quantity;
                            $subtotal += $itemTotal;
                        @endphp
                        <div class="product-row">
                            <img src="{{ asset('images/products/'.$item->product->image) }}" class="product-img me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $item->product->product_name }}</h6>
                                <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($price, 0, ',', '.') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold" style="color: #8B4513;">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- PILIH VOUCHER -->
            <div class="checkout-card card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-ticket-perforated me-2" style="color: #8B4513;"></i>Voucher Diskon
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-percent text-muted"></i>
                        </span>
                        <select id="select_voucher" name="voucher_id" class="form-select border-start-0">
                            <option value="" data-discount="0" data-min="0">-- Pilih Voucher --</option>
                            @foreach($userVouchers as $v)
                                <option value="{{ $v->id }}" data-discount="{{ $v->discount_percent }}" data-min="{{ $v->min_purchase }}">
                                    {{ $v->voucher_name }} ({{ $v->discount_percent }}%) - Min. Rp {{ number_format($v->min_purchase, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="voucher_msg" class="text-danger small mt-2"></div>
                    @if(count($userVouchers) == 0)
                        <div class="text-muted small mt-2">
                            <i class="bi bi-info-circle me-1"></i>Anda belum memiliki voucher
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RINGKASAN PEMBAYARAN -->
        <div class="col-lg-4">
            <div class="summary-card card sticky-top" style="top: 100px;">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-receipt me-2" style="color: #8B4513;"></i>Ringkasan Belanja
                    </h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="summary-row">
                        <span class="text-muted">Subtotal Produk</span>
                        <span id="display_subtotal" data-value="{{ $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-muted">Biaya Pengiriman</span>
                        <span id="display_shipping" data-value="0">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-muted">Potongan Voucher</span>
                        <span id="display_discount" class="text-success" data-value="0">- Rp 0</span>
                    </div>

                    <div class="total-row mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="opacity-75 d-block">Total Tagihan</small>
                                <h4 class="mb-0 fw-bold" id="display_total">Rp {{ number_format($subtotal, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4 pt-0">
                    <button type="submit" class="btn-checkout mb-2">
                        <i class="bi bi-shield-check me-2"></i>Proses Pesanan
                    </button>
                    <a href="/keranjang" class="btn btn-link w-100 text-muted text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
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

    // Method option styling
    $('.method-option').click(function() {
        $('.method-option').removeClass('active');
        $(this).addClass('active');
    });

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
            $('#voucher_msg').html('<i class="bi bi-exclamation-circle me-1"></i>Belanjaan belum cukup (Min. Rp ' + minPurchase.toLocaleString('id-ID') + ')');
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