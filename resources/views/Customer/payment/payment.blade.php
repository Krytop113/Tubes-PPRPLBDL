@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white text-center py-4 border-0">
                        <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                        <h5 class="mb-0 fw-bold">Konfirmasi Pembayaran</h5>
                        <p class="small mb-0 opacity-75">Order #{{ $order->id }}</p>
                    </div>

                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Total yang harus
                                dibayar</label>
                            <h2 class="fw-bold text-primary display-6">Rp {{ number_format($totalAmount, 0, ',', '.') }}
                            </h2>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <h6 class="fw-bold small mb-3 border-bottom pb-2">RINCIAN PESANAN</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0 small">
                                    <tr>
                                        <td class="text-muted">Subtotal Produk</td>
                                        <td class="text-end fw-bold text-dark">Rp
                                            {{ number_format($order->total_raw, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Biaya Pengiriman</td>
                                        <td class="text-end fw-bold text-dark">Rp
                                            {{ number_format($shippingCost, 0, ',', '.') }}</td>
                                    </tr>
                                    @if ($couponAmount > 0)
                                        <tr>
                                            <td class="text-success">Diskon Kupon</td>
                                            <td class="text-end fw-bold text-success">- Rp
                                                {{ number_format($couponAmount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="shipping_cost" value="{{ $shippingCost }}">
                            <input type="hidden" name="coupon_amount" value="{{ $couponAmount }}">
                            <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                            <input type="hidden" name="coupon_user_id" value="{{ $couponUserId }}">

                            <div class="mb-3">
                                <label class="fw-bold small mb-2 text-muted">METODE PEMBAYARAN</label>
                                <select name="method" class="form-select border-1 p-2 shadow-sm" required>
                                    <option value="" selected disabled>-- Pilih Bank Transfer --</option>
                                    <option value="Transfer BCA">Transfer BCA (123-456-789 a/n Nama Toko)</option>
                                    <option value="Transfer Mandiri">Transfer Mandiri (098-765-432 a/n Nama Toko)</option>
                                    <option value="Transfer BNI">Transfer BNI (555-666-777 a/n Nama Toko)</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold shadow-sm py-3">
                                    KONFIRMASI SEKARANG <i class="fas fa-check-circle ms-2"></i>
                                </button>
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="btn btn-link btn-sm text-muted text-decoration-none">
                                    Batal & Perbaiki Pesanan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    <p><i class="fas fa-shield-alt me-1"></i> Pembayaran Aman & Terverifikasi Otomatis</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .x-small {
            font-size: 0.75rem;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .form-select,
        .form-control {
            border-radius: 8px;
        }

        .card-header i {
            opacity: 0.8;
        }
    </style>
@endsection
