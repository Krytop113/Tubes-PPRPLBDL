@extends('layouts.app')

@section('content')
    <div class="container">

        <a href="{{ route('orders.index') }}" class="btn btn-secondary mb-3">
            ← Kembali
        </a>

        <h3 class="mb-3">Detail Order #{{ $order->id }}</h3>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p class="mb-1"><strong>Tanggal:</strong>
                    {{ Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}
                </p>
                <p class="mb-1"><strong>Status:</strong>
                    @switch($order->status)
                        @case('pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @break

                        @case('paid')
                            <span class="badge bg-info text-dark">Paid</span>
                        @break

                        @case('order')
                            <span class="badge bg-success">Selesai</span>
                        @break

                        @case('cancel')
                            <span class="badge bg-danger">Cancel</span>
                        @break
                    @endswitch
                </p>
                <p class="mb-0"><strong>Total:</strong>
                    Rp {{ number_format($order->total_raw, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderDetails as $orderDetails)
                            <tr>
                                <td>{{ $orderDetails->ingredient->name }}</td>
                                <td>Rp {{ number_format($orderDetails->price, 0, ',', '.') }}</td>
                                <td>{{ $orderDetails->quantity }}</td>
                                <td>
                                    Rp {{ number_format($orderDetails->price * $orderDetails->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>
                                Rp {{ number_format($order->total_raw, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if ($order->status === 'pending')
            <div class="alert alert-warning shadow-sm">
                <h6 class="mb-2 fw-bold">Instruksi Pembayaran</h6>
                <p class="mb-1">
                    Silakan lakukan pembayaran sesuai total berikut:
                </p>
                <p class="mb-2 fw-bold fs-5">
                    Rp {{ number_format($order->total_raw, 0, ',', '.') }}
                </p>
                <small class="text-muted">
                    Pembayaran berlaku maksimal 1x24 jam. Order akan otomatis dibatalkan jika tidak dibayar.
                </small>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Gunakan Kupon</h6>

                    @if ($couponUsers->isEmpty())
                        <div class="alert alert-secondary mb-0">
                            Anda tidak memiliki kupon yang dapat digunakan.
                        </div>
                    @else
                        <form action="{{ route('orders.applyCoupon', $order->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <select name="coupon_user_id" class="form-select" required>
                                    <option value="">-- Pilih Kupon --</option>
                                    @foreach ($couponUsers as $cu)
                                        <option value="{{ $cu->id }}">
                                            {{ $cu->coupon->title }}
                                            ({{ $cu->coupon->discount_type === 'percent'
                                                ? $cu->coupon->discount . '%'
                                                : 'Rp ' . number_format($cu->coupon->discount, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>

                                <button class="btn btn-primary" type="submit">
                                    Terapkan
                                </button>
                            </div>

                            @error('coupon_user_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </form>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin membatalkan order ini?')">
                    @csrf
                    @method('PUT')

                    <button class="btn btn-danger">
                        Batalkan Order
                    </button>
                </form>
            </div>
        @endif

    </div>
@endsection
