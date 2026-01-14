@extends('layouts.control')

@section('title', 'Laporan Pemesanan Lengkap')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h3 class="fw-bold text-dark mb-1">Pusat Laporan Pesanan</h3>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-3 justify-content-md-end">
                    <div class="card border-0 shadow-sm bg-white px-4 py-2">
                        <small class="text-muted d-block">Total Transaksi</small>
                        <span class="h5 fw-bold text-primary mb-0">{{ $orders->count() }}</span>
                    </div>
                    <div class="card border-0 shadow-sm bg-success text-white px-4 py-2">
                        <small class="opacity-75 d-block">Total Omzet</small>
                        <span class="h5 fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2 text-primary"></i>Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase">Tanggal Awal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase">Jenis Laporan</label>
                        <select name="report_type" class="form-select">
                            <option value="order_only" {{ request('report_type') == 'order_only' ? 'selected' : '' }}>
                                Ringkasan Order</option>
                            <option value="order_detail" {{ request('report_type') == 'order_detail' ? 'selected' : '' }}>
                                Order + Detail Barang</option>
                            <option value="top_items" {{ request('report_type') == 'top_items' ? 'selected' : '' }}>Barang
                                Terjual (Populer)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase">Cari Pelanggan</label>
                        <input type="text" name="user_search" class="form-control" placeholder="Nama atau ID User..."
                            value="{{ request('user_search') }}">
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-4">
                        <div class="d-flex gap-2">
                            <a href="{{ request()->url() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-search me-1"></i> Tampilkan Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('report_type') || request('report_type') == 'order_only' ? 'active' : '' }}"
                            href="#">Data Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('report_type') == 'top_items' ? 'active' : '' }}"
                            href="#">Produk Terlaris</a>
                    </li>
                </ul>
                <button class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order & Pelanggan</th>
                                <th>Tanggal</th>
                                @if (request('report_type') == 'order_detail')
                                    <th>Detail Barang</th>
                                @endif
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Metode</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">#{{ $order->order_id }}</div>
                                                <small class="text-muted">{{ $order->nama_pelanggan }} (UID:
                                                    {{ $order->user_id }})</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y') }}</small><br>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('H:i') }}
                                            WIB</small>
                                    </td>

                                    @if (request('report_type') == 'order_detail')
                                        <td>
                                            <ul class="list-unstyled mb-0 small">
                                                @php
                                                    $items = App\Models\OrderDetail::where(
                                                        'order_id',
                                                        $order->order_id,
                                                    )->get();
                                                @endphp
                                                @foreach ($items as $item)
                                                    <li><i class="fas fa-caret-right text-muted"></i>
                                                        {{ $item->product_name }} (x{{ $item->quantity }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    @endif

                                    <td>
                                        <span class="fw-bold">Rp
                                            {{ number_format($order->total_bayar ?? $order->subtotal, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $order->order_status == 'paid' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} border px-3 py-2">
                                            {{ strtoupper($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-credit-card me-1 small"></i>
                                            {{ $order->metode_pembayaran ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href=""
                                            class="btn btn-light btn-sm border" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ request('report_type') == 'order_detail' ? '7' : '6' }}"
                                        class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-muted opacity-50"></i>
                                        <p class="text-muted">Tidak ada data yang sesuai dengan kriteria filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
