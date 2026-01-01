@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Orders</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">{{ $order->id }}</li>
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
                            'paid' => 'bg-info-subtle text-info',
                            'done' => 'bg-success-subtle text-success',
                            'cancel' => 'bg-danger-subtle text-danger',
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
                                            <div class="fw-bold text-dark">{{ $item->ingredient->name }}</div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end pe-3 fw-bold">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
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

                        <form action="{{ route('payment') }}" method="POST" id="paymentForm">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Total Belanja</span>
                                <span class="fw-bold text-dark">Rp
                                    {{ number_format($order->total_raw, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Ongkos Kirim (Bandung Area)</span>
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
                                <span class="fw-bold text-primary fs-5">Rp <span
                                        id="totalAkhirDisplay">{{ number_format($order->total_raw, 0, ',', '.') }}</span></span>
                            </div>

                            @if ($order->status === 'pending')
                                <div class="mb-3 bg-light p-3 rounded-3 border border-primary-subtle">
                                    <h6 class="fw-bold x-small text-uppercase mb-3 text-primary"><i
                                            class="fas fa-truck me-1"></i> Pengiriman Khusus Bandung</h6>

                                    <div class="mb-2">
                                        <label class="x-small text-muted fw-bold">KECAMATAN TUJUAN</label>
                                        <select name="destination_area" id="destinationArea"
                                            class="form-select form-select-sm" required>
                                            <option value="" data-cost="0">-- Pilih Kecamatan --</option>
                                            <optgroup label="Zona 1 (Dekat)">
                                                <option value="Coblong" data-cost="10000">Coblong (Dago, Sadang Serang)
                                                </option>
                                                <option value="Lengkong" data-cost="10000">Lengkong (Burangrang, Malabar)
                                                </option>
                                                <option value="Cibeunying" data-cost="12000">Cibeunying Kidul/Kaler</option>
                                            </optgroup>
                                            <optgroup label="Zona 2 (Sedang)">
                                                <option value="Sukajadi" data-cost="15000">Sukajadi (Pasteur, PVJ)</option>
                                                <option value="Antapani" data-cost="18000">Antapani</option>
                                                <option value="Arcamanik" data-cost="20000">Arcamanik</option>
                                            </optgroup>
                                            <optgroup label="Zona 3 (Jauh)">
                                                <option value="Cibiru" data-cost="25000">Cibiru / Ujung Berung</option>
                                                <option value="Gedebage" data-cost="25000">Gedebage</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="x-small text-muted fw-bold">METODE KURIR</label>
                                        <select name="courier" class="form-select form-select-sm">
                                            <option value="kurir_toko">Kurir Internal Toko</option>
                                            <option value="gosend">GoSend / GrabExpress</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-bold x-small text-uppercase mb-2">Gunakan Kupon</h6>
                                    <select name="coupon_user_id" class="form-select form-select-sm" id="couponSelect">
                                        <option value="" data-discount="0">-- Pilih Kupon (Jika ada) --</option>
                                        @foreach ($couponUsers as $cu)
                                            <option value="{{ $cu->id }}"
                                                data-discount="{{ $cu->coupon->discount_percentage }}">
                                                {{ $cu->coupon->title }} ({{ $cu->coupon->discount_percentage }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" id="btnSubmitPayment"
                                        class="btn btn-primary fw-bold py-3 rounded-3 shadow-sm" disabled>
                                        LANJUT KE PEMBAYARAN <i class="fas fa-chevron-right ms-2"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-link text-danger btn-sm fw-bold text-decoration-none"
                                        data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                        Batalkan Pesanan Ini
                                    </button>
                                </div>
                            @endif
                        </form>
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
                    <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="d-grid gap-2">
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

    <style>
        .x-small {
            font-size: 0.75rem;
        }

        .bg-warning-subtle {
            background-color: #fff3cd;
            color: #856404;
        }

        .bg-info-subtle {
            background-color: #cff4fc;
            color: #055160;
        }

        .bg-success-subtle {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .bg-danger-subtle {
            background-color: #f8d7da;
            color: #842029;
        }

        .form-select-sm {
            border-radius: 8px;
        }
    </style>

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

            el.area.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const cost = parseInt(selectedOption.getAttribute('data-cost')) || 0;

                currentOngkir = cost;
                el.costInput.value = cost;

                el.btnSubmit.disabled = (cost === 0);

                calculateFinal();
            });

            if (el.coupon) {
                el.coupon.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    currentDiscountPercent = parseInt(selectedOption.getAttribute('data-discount')) || 0;
                    calculateFinal();
                });
            }
        });
    </script>
@endsection
