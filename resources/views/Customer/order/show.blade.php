@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Orders</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">#{{ $order->id }}</li>
            </ol>
        </nav>

        <div class="mb-4">
            <a href="{{ route('orders.index') }}" class="text-decoration-none text-muted small fw-bold">
                <i class="fas fa-arrow-left me-1"></i> KEMBALI KE DAFTAR PESANAN
            </a>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <h3 class="fw-bold mb-0">Detail Order <span class="text-primary">#{{ $order->id }}</span></h3>
                <div>
                    @php
                        $statusBadge = [
                            'pending' => 'bg-warning-subtle text-warning',
                            'paid'    => 'bg-info-subtle text-info',
                            'order'   => 'bg-success-subtle text-success',
                            'cancel'  => 'bg-danger-subtle text-danger',
                        ];
                    @endphp
                    <span class="badge rounded-pill {{ $statusBadge[$order->status] ?? 'bg-secondary' }} px-3 py-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 ps-4">
                        <h6 class="fw-bold mb-0">Item Pesanan</h6>
                    </div>
                    <div class="table-responsive p-3">
                        <table class="table align-middle">
                            <thead class="text-muted small text-uppercase">
                                <tr>
                                    <th class="border-0 ps-3">Produk</th>
                                    <th class="border-0">Harga</th>
                                    <th class="border-0 text-center">Jumlah</th>
                                    <th class="border-0 text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderDetails as $item)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                @if (isset($item->ingredient_image) && $item->ingredient_image)
                                                    <img src="{{ asset('ingredients/' . $item->ingredient_image) }}"
                                                        class="rounded me-2" width="40">
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $item->ingredient_name }}</div>
                                                    <small class="text-muted">{{ $item->ingredient_unit }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->detail_price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end pe-3 fw-bold">
                                            Rp {{ number_format($item->detail_price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Pembayaran</h6>

                        @if ($order->status === 'paid' || $order->status === 'order')
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Total Belanja</span>
                                <span class="fw-bold text-dark">Rp {{ number_format($order->total_raw, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Ongkos Kirim</span>
                                <span class="fw-bold text-dark">Rp {{ number_format($payment->shipping_cost ?? 0, 0, ',', '.') }}</span>
                            </div>

                            @if (($payment->coupon_amount ?? 0) > 0)
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-success">Potongan Kupon</span>
                                    <span class="text-success fw-bold">- Rp {{ number_format($payment->coupon_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <hr class="text-muted opacity-25">

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold text-dark">Total Bayar</span>
                                <span class="fw-bold text-primary fs-5">Rp {{ number_format($payment->total_amount ?? $order->total_raw, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" onclick="window.print()" class="btn btn-outline-primary fw-bold py-2 rounded-3">
                                    <i class="fas fa-print me-2"></i> CETAK NOTA
                                </button>
                            </div>
                        
                        @elseif($order->status === 'pending')
                            <form action="{{ route('payment') }}" method="POST" id="paymentForm">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Total Belanja</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($order->total_raw, 0, ',', '.') }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Ongkos Kirim</span>
                                    <span class="fw-bold text-dark" id="ongkirDisplay">Rp 0</span>
                                </div>

                                <div id="discountSection" class="d-none">
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="text-success">Potongan Kupon</span>
                                        <span class="text-success fw-bold" id="totalPotongan">- Rp 0</span>
                                    </div>
                                </div>

                                <hr class="text-muted opacity-25">

                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fw-bold text-dark">Total Bayar</span>
                                    <span class="fw-bold text-primary fs-5">Rp <span id="totalAkhirDisplay">{{ number_format($order->total_raw, 0, ',', '.') }}</span></span>
                                </div>

                                <div class="mb-3 bg-light p-3 rounded-3 border border-primary-subtle">
                                    <h6 class="fw-bold x-small text-uppercase mb-3 text-primary">
                                        <i class="fas fa-truck me-1"></i> Pengiriman Area Bandung
                                    </h6>
                                    <div class="mb-2">
                                        <label class="x-small text-muted fw-bold">KECAMATAN TUJUAN</label>
                                        <select name="destination_area" id="destinationArea" class="form-select form-select-sm" required>
                                            <option value="" data-cost="0">-- Pilih Kecamatan --</option>
                                            <optgroup label="Zona 1 (Rp 10.000)">
                                                <option value="Coblong" data-cost="10000">Coblong</option>
                                                <option value="Lengkong" data-cost="10000">Lengkong</option>
                                            </optgroup>
                                            <optgroup label="Zona 2 (Rp 15.000 - 20.000)">
                                                <option value="Sukajadi" data-cost="15000">Sukajadi</option>
                                                <option value="Antapani" data-cost="18000">Antapani</option>
                                                <option value="Arcamanik" data-cost="20000">Arcamanik</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-bold x-small text-uppercase mb-2">Gunakan Kupon</h6>
                                    <select name="coupon_user_id" class="form-select form-select-sm" id="couponSelect">
                                        <option value="" data-discount="0">-- Pilih Kupon --</option>
                                        @foreach ($couponUsers as $cu)
                                            <option value="{{ $cu->coupon_user_id }}" data-discount="{{ $cu->discount_percentage }}">
                                                {{ $cu->title }} ({{ $cu->discount_percentage }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" id="btnSubmitPayment" class="btn btn-primary fw-bold py-3 rounded-3 shadow-sm" disabled>
                                        LANJUT KE PEMBAYARAN <i class="fas fa-chevron-right ms-2"></i>
                                    </button>
                                    <button type="button" class="btn btn-link text-danger btn-sm fw-bold text-decoration-none"
                                        data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                        Batalkan Pesanan
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-times-circle text-danger fa-3x mb-2"></i>
                                <p class="text-muted small">Pesanan ini telah dibatalkan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Batalkan Pesanan?</h5>
                    <p class="small text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="d-grid gap-2 mt-4">
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger w-100 fw-bold">Ya, Batalkan</button>
                        </form>
                        <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalRaw = parseInt("{{ $order->total_raw }}");
            let currentOngkir = 0;
            let currentDiscountPercent = 0;

            const el = {
                area: document.getElementById('destinationArea'),
                coupon: document.getElementById('couponSelect'),
                displayOngkir: document.getElementById('ongkirDisplay'),
                displayDiscount: document.getElementById('totalPotongan'),
                displayTotal: document.getElementById('totalAkhirDisplay'),
                discountDiv: document.getElementById('discountSection'),
                costInput: document.getElementById('shippingCostInput'),
                btnSubmit: document.getElementById('btnSubmitPayment')
            };

            function calculateFinal() {
                const discountAmount = (currentDiscountPercent / 100) * totalRaw;
                const grandTotal = (totalRaw - discountAmount) + currentOngkir;

                if (discountAmount > 0) {
                    el.discountDiv.classList.remove('d-none');
                    el.displayDiscount.innerText = '- Rp ' + discountAmount.toLocaleString('id-ID');
                } else {
                    el.discountDiv.classList.add('d-none');
                }

                el.displayOngkir.innerText = 'Rp ' + currentOngkir.toLocaleString('id-ID');
                el.displayTotal.innerText = grandTotal.toLocaleString('id-ID');
            }

            if(el.area) {
                el.area.addEventListener('change', function() {
                    const cost = parseInt(this.options[this.selectedIndex].getAttribute('data-cost')) || 0;
                    currentOngkir = cost;
                    el.costInput.value = cost;
                    el.btnSubmit.disabled = (cost === 0);
                    calculateFinal();
                });
            }

            if (el.coupon) {
                el.coupon.addEventListener('change', function() {
                    currentDiscountPercent = parseInt(this.options[this.selectedIndex].getAttribute('data-discount')) || 0;
                    calculateFinal();
                });
            }
        });
    </script>
@endsection