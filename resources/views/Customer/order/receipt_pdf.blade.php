<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Resi Pesanan #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.6;
        }

        .receipt-box {
            padding: 10px;
        }

        .header {
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .brand {
            font-size: 26px;
            font-weight: bold;
            color: #0d6efd;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table td {
            vertical-align: top;
            width: 50%;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
        }

        table.items th {
            background: #f4f7fe;
            padding: 12px 10px;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
            color: #555;
            font-size: 11px;
            text-transform: uppercase;
        }

        table.items td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }

        .totals-container {
            margin-top: 20px;
            width: 100%;
        }

        .totals-table {
            width: 40%;
            margin-left: 60%;
        }

        .totals-table td {
            padding: 5px 0;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            border-top: 2px solid #0d6efd;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        img {
            image-rendering: -webkit-optimize-contrast;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <div class="receipt-box">
        <div class="header">
            <table style="width: 100%;">
                <tr>
                    <td class="brand">
                        <img src="{{ $logo }}" width="40" height="40" style="display: block;">
                        <span style="vertical-align: middle;">KRIUK KRIUK</span>
                    </td>
                    <td class="text-right">
                        <h2 style="margin:0; color: #333;">RESI PEMBAYARAN</h2>
                        <span style="color: #666;">ID Pesanan: #{{ $order->id }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <strong style="color: #0d6efd;">Diterbitkan Untuk:</strong><br>
                    <span style="font-size: 15px; font-weight: bold;">{{ Auth::user()->name }}</span><br>
                    {{ Auth::user()->email }}
                </td>
                <td class="text-right">
                    <strong>Tanggal Transaksi:</strong><br>
                    {{ $order->created_at->format('d F Y, H:i') }} WIB<br>
                    <strong>Metode Pembayaran:</strong> {{ $payment->method }}<br>
                    <strong>Status:</strong> <span style="color: #198754; font-weight: bold;">{{ $order->status }}</span>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Deskripsi Produk</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderDetails as $item)
                    <tr>
                        <td>
                            <div style="font-weight: bold;">{{ $item->ingredient->name ?? 'Produk Terhapus' }}</div>
                            <small style="color: #777;">Satuan: {{ $item->ingredient->unit ?? 'N/A' }}</small>
                        </td>
                        <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp
                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-container">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp {{ number_format($order->total_raw, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <td class="text-right">Rp {{ number_format($payment->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                @if ($payment->coupon_amount > 0)
                    <tr style="color: #198754;">
                        <td>Potongan Kupon</td>
                        <td class="text-right">- Rp {{ number_format($payment->coupon_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="grand-total">
                    <td style="padding-top: 10px;">Total Bayar</td>
                    <td class="text-right" style="padding-top: 10px;">Rp
                        {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja! Pesanan Anda sedang kami proses untuk pengiriman.</p>
            <p>Ini adalah dokumen yang dihasilkan secara otomatis dan sah sebagai bukti pembayaran resmi.</p>
        </div>
    </div>
</body>

</html>
