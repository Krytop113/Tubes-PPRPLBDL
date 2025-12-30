@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}"
                        class="text-decoration-none">Orders</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">{{ $order->id }}
                </li>
            </ol>
        </nav>

        <div class="mb-8">
            <a href="{{ route('orders.index') }}" class="text-decoration-none text-muted small fw-bold">
                <i class="fas fa-arrow-left me-1"></i> KEMBALI KE DAFTAR PESANAN
            </a>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <h3 class="fw-bold mb-0">Detail Order <span class="text-primary">#{{ $order->id }}</span></h3>
                <div>
                    @switch($order->status)
                        @case('pending')
                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3">Pending</span>
                        @break

                        @case('paid')
                            <span class="badge rounded-pill bg-info-subtle text-info px-3">Paid</span>
                        @break

                        @case('order')
                            <span class="badge rounded-pill bg-success-subtle text-success px-3">Selesai</span>
                        @break

                        @case('cancel')
                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Dibatalkan</span>
                        @break
                    @endswitch
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
                            <thead class="text-muted small uppercase">
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

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tanggal Pesan</span>
                            <span
                                class="fw-medium small text-end">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</span>
                        </div>

                        <hr class="text-muted opacity-25">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Awal</span>
                            <span class="fw-bold text-dark">Rp {{ number_format($order->total_raw, 0, ',', '.') }}</span>
                        </div>

                        <div id="discountSection" class="d-none">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success">Potongan Kupon</span>
                                <span class="text-success fw-bold" id="totalPotongan">- Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 border-top pt-2 mt-2">
                                <span class="fw-bold text-dark">Total Akhir</span>
                                <span class="fw-bold text-primary fs-5">Rp <span id="totalAkhir">0</span></span>
                            </div>
                        </div>

                        @if ($order->status === 'pending')
                            <div class="mt-4">
                                <h6 class="fw-bold small mb-2 text-uppercase">Pilih Kupon Hemat</h6>
                                @if ($couponUsers->isEmpty())
                                    <div class="bg-light p-2 rounded text-center small text-muted">
                                        Tidak ada kupon tersedia
                                    </div>
                                @else
                                    <form action="{{ route('orders.applyCoupon', $order->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group input-group-sm mb-2">
                                            <select name="coupon_user_id" class="form-select border-primary-subtle"
                                                id="couponSelect" required>
                                                <option value="">-- Pilih Kupon --</option>
                                                @foreach ($couponUsers as $cu)
                                                    <option value="{{ $cu->id }}"
                                                        data-discount="{{ $cu->coupon->discount_percentage }}">
                                                        {{ $cu->coupon->title }} ({{ $cu->coupon->discount_percentage }}%)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary" type="submit">Gunakan</button>
                                        </div>
                                        @error('coupon_user_id')
                                            <small class="text-danger d-block mb-2">{{ $message }}</small>
                                        @enderror
                                    </form>
                                @endif
                            </div>

                            <div class="mt-4 p-3 bg-warning-subtle rounded-3">
                                <h6 class="small fw-bold text-warning-emphasis"><i class="fas fa-info-circle me-1"></i>
                                    Instruksi</h6>
                                <p class="mb-0 x-small text-muted" style="font-size: 0.75rem;">
                                    Pembayaran berlaku maksimal 1x24 jam. Gunakan kode unik jika diminta pada saat transfer.
                                </p>
                            </div>

                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan order ini?')" class="mt-3 text-center">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-link btn-sm text-danger text-decoration-none">
                                    <i class="fas fa-trash-alt me-1"></i> Batalkan Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-warning-subtle {
            background-color: #fff3cd !important;
            color: #856404 !important;
        }

        .bg-info-subtle {
            background-color: #cff4fc !important;
            color: #055160 !important;
        }

        .bg-success-subtle {
            background-color: #d1e7dd !important;
            color: #0f5132 !important;
        }

        .bg-danger-subtle {
            background-color: #f8d7da !important;
            color: #842029 !important;
        }

        .uppercase {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.7rem;
        }
    </style>

    <script>
        const couponSelect = document.getElementById('couponSelect');
        const totalAwal = {{ $order->total_raw }};
        const totalAkhirEl = document.getElementById('totalAkhir');
        const totalPotonganEl = document.getElementById('totalPotongan');
        const totalAfterWrapper = document.getElementById('discountSection');

        if (couponSelect) {
            couponSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const discount = selectedOption.getAttribute('data-discount');

                if (!discount) {
                    totalAfterWrapper.classList.add('d-none');
                    return;
                }

                const discountValue = (discount / 100) * totalAwal;
                const finalTotal = totalAwal - discountValue;

                totalPotonganEl.innerText = '- Rp ' + discountValue.toLocaleString('id-ID');
                totalAkhirEl.innerText = finalTotal.toLocaleString('id-ID');
                totalAfterWrapper.classList.remove('d-none');
            });
        }
    </script>
@endsection
