@extends('layouts.control')

@section('title', 'Laporan Order Selesai')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h3 class="fw-bold text-dark mb-1">Laporan Order: Paid & Done</h3>
                <p class="text-muted">Periode:
                    {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Awal' }}
                    s/d
                    {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Sekarang' }}
                </p>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-body p-3 text-center">
                        <small class="text-muted d-block mb-1">Total Transaksi</small>
                        <span class="h5 fw-bold text-primary">{{ $orders->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body p-3 text-center">
                        <small class="opacity-75 d-block mb-1">Total Omzet</small>
                        <span class="h5 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase">Tanggal Awal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="start_date" class="form-control border-start-0"
                                value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase">Tanggal Akhir</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-calendar-check"></i></span>
                            <input type="date" name="end_date" class="form-control border-start-0"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ request()->url() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
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
                                <th class="ps-4 py-3">ID Order</th>
                                <th>Pelanggan</th>
                                <th>Tanggal Transaksi</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $order->customer_name }}</span>
                                            <small class="text-muted">UID: {{ $order->user_id }}</small>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">
                                            Rp {{ number_format($order->total_raw, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($order->status == 'paid')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i> PAID
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                                <i class="fas fa-flag-checkered me-1"></i> DONE
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Tidak ada data order ditemukan untuk periode ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1">
                            <i class="fas fa-print"></i> Download Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
