@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white text-center py-4 border-0"
                        style="background: linear-gradient(45deg, #0d6efd, #004fb1);">
                        <div class="payment-icon-wrapper mb-3">
                            <i class="fas fa-shield-check fa-3x"></i>
                        </div>
                        <h5 class="mb-1 fw-bold">Konfirmasi Pembayaran</h5>
                        <p class="small mb-0 opacity-75">ID Pesanan: #{{ $order->id }}</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <span
                                class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-2">Total
                                Tagihan</span>
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
                                    Transfer</label>
                                <select name="method" class="form-select form-select-lg border-2 shadow-sm custom-select"
                                    required>
                                    <option value="" selected disabled>-- Pilih Rekening Tujuan --</option>
                                    <option value="Transfer BCA">BCA - 123456789 (a/n Toko Kami)</option>
                                    <option value="Transfer Mandiri">Mandiri - 098765432 (a/n Toko Kami)</option>
                                    <option value="Transfer BNI">BNI - 555666777 (a/n Toko Kami)</option>
                                </select>
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

                            <div class="d-grid gap-2">
                                <button type="submit"
                                    class="btn btn-primary btn-lg rounded-3 fw-bold shadow py-3 transition-all">
                                    BAYAR SEKARANG <i class="fas fa-check-circle ms-2"></i>
                                </button>
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="btn btn-link btn-sm text-muted text-decoration-none mt-2">
                                    <i class="fas fa-chevron-left me-1"></i> Batal & Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <div class="d-flex align-items-center justify-content-center gap-3 opacity-50">
                        <i class="fab fa-cc-visa fa-2x"></i>
                        <i class="fab fa-cc-mastercard fa-2x"></i>
                        <i class="fas fa-shield-alt fa-2x"></i>
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

        .payment-icon-wrapper {
            background: rgba(255, 255, 255, 0.15);
            display: inline-block;
            padding: 20px;
            border-radius: 50%;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
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
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah Anda yakin ingin melanjutkan pembayaran dengan metode ini?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'rounded-3 px-4',
                    cancelButton: 'rounded-3 px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); 
                }
            });
        });
    </script>
@endsection
