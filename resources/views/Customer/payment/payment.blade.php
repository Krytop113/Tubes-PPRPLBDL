@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white text-center py-4 border-0"
                        style="background: linear-gradient(45deg, #0d6efd, #004fb1);">
                        <h5 class="mb-1 fw-bold">Konfirmasi Pembayaran</h5>
                        <p class="small mb-0 opacity-75">ID Pesanan: #{{ $order->id }}</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <span
                                class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-2">
                                Total Tagihan
                            </span>
                            <h2 class="fw-bold text-dark display-6 mb-0">
                                <span class="fs-4 align-top mt-2">Rp</span>{{ number_format($totalAmount, 0, ',', '.') }}
                            </h2>
                        </div>

                        <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data"
                            id="payment-form">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="shipping_cost" value="{{ $shippingCost }}">
                            <input type="hidden" name="coupon_amount" value="{{ $couponAmount }}">
                            <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                            <input type="hidden" name="coupon_user_id" value="{{ $couponUserId }}">

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase text-muted ls-1">Pilih Metode
                                    Pembayaran</label>
                                <select name="method" id="payment-method-select"
                                    class="form-select form-select-lg border-2 shadow-sm custom-select" required>
                                    <option value="" selected disabled>-- Pilih Cara Bayar --</option>
                                    <option value="BCA" data-prefix="1234">Virtual Account BCA</option>
                                    <option value="Mandiri" data-prefix="5678">Virtual Account Mandiri</option>
                                    <option value="BNI" data-prefix="9999">Virtual Account BNI</option>
                                    <option value="QRIS">QRIS (OVO, Dana, LinkAja)</option>
                                </select>
                            </div>

                            <div id="payment-instruction" class="text-center p-4 border rounded-4 bg-light mb-4 d-none"
                                style="cursor: pointer; border-style: dashed !important;">

                                <div id="va-container" class="d-none">
                                    <h3 class="fw-bold text-primary mb-1" id="va-number"></h3>
                                    <span class="badge bg-primary-subtle text-primary">Virtual Account <span
                                            id="bank-name"></span></span>
                                </div>

                                <div id="qris-container" class="d-none">
                                    <p class="text-muted small mb-3">Scan kode QR di bawah untuk membayar:</p>
                                    <img src="{{ asset('qris.png') }}" alt="QRIS" class="img-fluid rounded-3 mb-2"
                                        style="max-width: 200px;">
                                </div>

                            </div>

                            <div class="bg-light rounded-3 p-3 mb-4 border-start border-primary border-4 shadow-sm">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Subtotal Produk</span>
                                    <span class="small fw-bold text-dark">Rp
                                        {{ number_format($order->total_raw, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Ongkos Kirim</span>
                                    <span class="small fw-bold text-dark">Rp
                                        {{ number_format($shippingCost, 0, ',', '.') }}</span>
                                </div>
                                @if ($couponAmount > 0)
                                    <div class="d-flex justify-content-between text-success">
                                        <span class="small">Diskon Kupon</span>
                                        <span class="small fw-bold">- Rp
                                            {{ number_format($couponAmount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center">
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="btn btn-link btn-sm text-muted text-decoration-none">
                                    <i class="fas fa-chevron-left me-1"></i> Batal & Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 opacity-50">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="fas fa-university fa-lg"></i>
                        <i class="fas fa-qrcode fa-lg"></i>
                        <i class="fas fa-shield-alt fa-lg"></i>
                    </div>
                    <p class="text-muted x-small mt-2 mb-0">Pembayaran aman dengan enkripsi SSL</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f4f7fe;
            font-family: 'Inter', sans-serif;
        }

        .x-small {
            font-size: 0.75rem;
        }

        .ls-1 {
            letter-spacing: 0.5px;
        }

        .custom-select {
            cursor: pointer;
            border-color: #e0e6ed;
        }
    </style>

    <script>
        const select = document.getElementById('payment-method-select');
        const instructionArea = document.getElementById('payment-instruction');
        const vaContainer = document.getElementById('va-container');
        const qrisContainer = document.getElementById('qris-container');
        const vaNumberDisplay = document.getElementById('va-number');
        const bankNameDisplay = document.getElementById('bank-name');

        const userPhone = "{{ Auth::user()->phone ?? '08123456789' }}";

        select.addEventListener('change', function() {
            const method = this.value;
            const prefix = this.options[this.selectedIndex].getAttribute('data-prefix');

            instructionArea.classList.remove('d-none');
            vaContainer.classList.add('d-none');
            qrisContainer.classList.add('d-none');

            if (method === 'QRIS') {
                qrisContainer.classList.remove('d-none');
            } else {
                vaContainer.classList.remove('d-none');
                vaNumberDisplay.innerText = prefix + '-' + userPhone;
                bankNameDisplay.innerText = method;
            }
        });

        instructionArea.addEventListener('click', function() {
            instructionArea.innerHTML =
                '<div class="spinner-border text-primary" role="status"></div><p class="mt-2 mb-0">Memproses Pembayaran...</p>';

            setTimeout(() => {
                document.getElementById('payment-form').submit();
            }, 1000);
        });
    </script>
@endsection
