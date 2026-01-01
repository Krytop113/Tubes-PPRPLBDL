@extends('layouts.control')

@section('title', 'Laporan Order Selesai')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">Monitor Order: Paid & Done</h3>
            <span class="badge bg-primary px-3 py-2">{{ $orders->count() }} Total Transaksi</span>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Cari Nama Pelanggan atau ID Order..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $order->customer_name }}</span>
                                            <small class="text-muted">User ID: {{ $order->user_id }}</small>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="text-success fw-bold">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($order->status == 'paid')
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i> PAID
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2">
                                                <i class="fas fa-flag-checkered me-1"></i> DONE
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="#" class="btn btn-sm btn-light border">
                                            <i class="fas fa-file-invoice-dollar me-1"></i> Invoice
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <p>Tidak ada data order dengan status Paid atau Done.</p>
                                        </div>
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
