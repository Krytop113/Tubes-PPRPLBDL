@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Orders</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">#{{ $order->id }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 ps-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-shopping-basket me-2 text-primary"></i>Item
                            Pesanan</h6>
                    </div>
                    <div class="table-responsive px-3 pb-3">
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
                                                        class="rounded-3 me-3 shadow-sm" width="50" height="50"
                                                        style="object-fit: cover;">
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $item->ingredient_name }}</div>
                                                    <small class="text-muted">{{ $item->ingredient_unit }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->detail_price, 0, ',', '.') }}</td>
                                        <td class="text-center"><span
                                                class="badge bg-light text-dark border">{{ $item->quantity }}</span></td>
                                        <td class="text-end pe-3 fw-bold text-dark">
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
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header text-white text-center py-4 border-0"
                        style="background: linear-gradient(45deg, #0d6efd, #004fb1);">
                        <h5 class="mb-1 fw-bold">Ringkasan Pesanan</h5>
                        <div class="badge bg-white bg-opacity-25 rounded-pill px-3 py-1 small">
                            Status: {{ ucfirst($order->status) }}
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if ($order->status === 'pending')
                            <form action="{{ route('payment') }}" method="POST" id="payment-form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-muted ls-1">Area
                                        Pengiriman</label>
                                    <select name="destination_area" id="destinationArea"
                                        class="form-select form-select-lg border-2 shadow-sm custom-select" required>
                                        <option value="" data-cost="0" selected disabled>-- Pilih Kecamatan --
                                        </option>
                                        <optgroup label="Zona 1 (Rp 10.000)">
                                            <option value="Coblong" data-cost="10000">Coblong</option>
                                            <option value="Lengkong" data-cost="10000">Lengkong</option>
                                        </optgroup>
                                        <optgroup label="Zona 2 (Rp 15.000 - 20.000)">
                                            <option value="Sukajadi" data-cost="15000">Sukajadi</option>
                                            <option value="Antapani" data-cost="18000">Antapani</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-muted ls-1">Gunakan
                                        Kupon</label>
                                    <select name="coupon_user_id" class="form-select border-2 shadow-sm custom-select"
                                        id="couponSelect">
                                        <option value="" data-discount="0">Tanpa Kupon</option>
                                        @foreach ($couponUsers as $cu)
                                            <option value="{{ $cu->coupon_user_id }}"
                                                data-discount="{{ $cu->discount_percentage }}">
                                                {{ $cu->title }} ({{ $cu->discount_percentage }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="bg-light rounded-3 p-3 mb-4 border-start border-primary border-4 shadow-sm">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Subtotal</span>
                                        <span class="small fw-bold text-dark">Rp
                                            {{ number_format($order->total_raw, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Ongkos Kirim</span>
                                        <span class="small fw-bold text-dark" id="ongkirDisplay">Rp 0</span>
                                    </div>
                                    <div id="discountSection" class="d-none">
                                        <div class="d-flex justify-content-between text-success">
                                            <span class="small">Potongan Kupon</span>
                                            <span class="small fw-bold" id="totalPotongan">- Rp 0</span>
                                        </div>
                                    </div>
                                    <hr class="my-2 opacity-25">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark">Total Akhir</span>
                                        <span class="fw-bold text-primary fs-5">Rp <span
                                                id="totalAkhirDisplay">{{ number_format($order->total_raw, 0, ',', '.') }}</span></span>
                                    </div>
                                </div>

                                <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">

                                <div class="d-grid gap-2">
                                    <button type="submit" id="btnSubmitPayment"
                                        class="btn btn-primary btn-lg rounded-3 fw-bold shadow py-3 transition-all"
                                        disabled>
                                        LANJUT KE PEMBAYARAN <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>

                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" id="cancel-form"
                                class="mt-2 text-center">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn btn-link btn-sm text-danger text-decoration-none fw-bold">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                @if ($order->status === 'cancel')
                                    <i class="fas fa-times-circle text-danger fa-4x mb-3"></i>
                                    <h6 class="fw-bold text-muted">Pesanan Dibatalkan</h6>
                                @else
                                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                                    <h6 class="fw-bold">Pembayaran Berhasil</h6>
                                @endif
                                <a href="{{ route('orders.index') }}"
                                    class="btn btn-outline-secondary btn-sm mt-3 rounded-pill px-4">Kembali ke Daftar</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f4f7fe;
            font-family: 'Inter', sans-serif;
        }

        .ls-1 {
            letter-spacing: 0.5px;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .transition-all:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3) !important;
        }

        .custom-select {
            cursor: pointer;
            border-color: #e0e6ed;
        }

        .custom-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                btnSubmit: document.getElementById('btnSubmitPayment'),
                paymentForm: document.getElementById('payment-form'),
                cancelForm: document.getElementById('cancel-form')
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

            el.area.addEventListener('change', function() {
                const cost = parseInt(this.options[this.selectedIndex].getAttribute('data-cost')) || 0;
                currentOngkir = cost;
                el.costInput.value = cost;
                el.btnSubmit.disabled = (cost === 0);
                calculateFinal();
            });

            el.coupon.addEventListener('change', function() {
                currentDiscountPercent = parseInt(this.options[this.selectedIndex].getAttribute(
                    'data-discount')) || 0;
                calculateFinal();
            });

            el.paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Pesanan',
                    text: "Lanjutkan ke pemilihan metode pembayaran?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Cek Lagi',
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            el.cancelForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Batalkan Pesanan?',
                    text: "Tindakan ini akan menghapus antrean pesanan Anda.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tutup',
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
