@extends('layouts.control')

@section('title', 'Laporan Pemesanan')

@section('content')
    <div class="container-fluid">
        {{-- Header & Stats --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h3 class="fw-bold text-dark mb-1">Laporan Transaksi</h3>
                <p class="text-muted small">Menampilkan data berstatus <span class="badge bg-success">PAID</span> & <span
                        class="badge bg-primary">DONE</span></p>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-3 justify-content-md-end">
                    <div class="card border-0 shadow-sm bg-white px-4 py-2 text-center">
                        <small class="text-muted d-block">Jumlah Baris</small>
                        <span class="h5 fw-bold text-primary mb-0">{{ $orders->count() }}</span>
                    </div>
                    <div class="card border-0 shadow-sm bg-success text-white px-4 py-2 text-center">
                        <small class="opacity-75 d-block">Total Nilai</small>
                        <span class="h5 fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Periode & Cari --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="" method="GET" class="row g-3 align-items-end">
                    {{-- Input report_type disembunyikan agar saat submit filter, tab tidak berubah --}}
                    <input type="hidden" name="report_type" value="{{ request('report_type', 'order_only') }}">

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Cari Data</label>
                        <input type="text" name="user_search" class="form-control"
                            placeholder="Nama pelanggan atau ID..." value="{{ request('user_search') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ request()->url() }}" class="btn btn-light border" title="Reset">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                {{-- Tombol Pembeda Tab --}}
                <div class="btn-group p-1 bg-light rounded shadow-sm">
                    <a href="{{ request()->fullUrlWithQuery(['report_type' => 'order_only']) }}"
                        class="btn btn-sm px-4 {{ request('report_type') != 'top_items' ? 'btn-white shadow-sm fw-bold' : 'text-muted' }}">
                        <i class="fas fa-list me-1"></i> Data Pesanan
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['report_type' => 'top_items']) }}"
                        class="btn btn-sm px-4 {{ request('report_type') == 'top_items' ? 'btn-white shadow-sm fw-bold' : 'text-muted' }}">
                        <i class="fas fa-chart-line me-1"></i> Produk Terlaris
                    </a>
                </div>

                <button class="btn btn-success btn-sm px-3">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase">
                            <tr style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                @if (request('report_type') == 'top_items')
                                    <th class="ps-4 py-3">Bahan / Ingredient</th>
                                    <th>Satuan</th>
                                    <th>Total Kuantitas</th>
                                    <th>Frekuensi</th>
                                    <th class="text-end pe-4">Subtotal Omzet</th>
                                @else
                                    <th class="ps-4 py-3">Info Pesanan</th>
                                    <th>Waktu</th>
                                    <th>Detail Barang</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    @if (request('report_type') == 'top_items')
                                        <td class="ps-4 fw-bold text-dark">{{ $order->nama_bahan }}</td>
                                        <td>{{ $order->satuan }}</td>
                                        <td><span
                                                class="badge bg-primary-subtle text-primary px-3">{{ $order->total_kuantitas }}</span>
                                        </td>
                                        <td>{{ $order->total_kali_dipesan }}x Transaksi</td>
                                        <td class="text-end pe-4 fw-bold text-success">Rp
                                            {{ number_format($order->total_omzet_bahan, 0, ',', '.') }}</td>
                                    @else
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">#{{ $order->order_id }}</div>
                                            <small class="text-muted">{{ $order->nama_pelanggan }}</small>
                                        </td>
                                        <td>
                                            <small
                                                class="d-block fw-bold">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y') }}</small>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled mb-0 small text-muted">
                                                @isset($order->items)
                                                    @foreach ($order->items as $item)
                                                        <li>• {{ $item->ingredient->name ?? 'N/A' }} ({{ $item->quantity }})
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li class="fst-italic">Ringkasan saja</li>
                                                @endisset
                                            </ul>
                                        </td>
                                        <td class="fw-bold">Rp
                                            {{ number_format($order->total_bayar ?? $order->subtotal, 0, ',', '.') }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $order->order_status == 'paid' ? 'bg-success' : 'bg-primary' }} rounded-pill"
                                                style="font-size: 0.65rem;">
                                                {{ strtoupper($order->order_status) }}
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                        <p>Data tidak ditemukan pada periode ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-white {
            background: #fff;
            color: #333;
        }

        .btn-white:hover {
            background: #f8f9fa;
        }
    </style>
@endsection
