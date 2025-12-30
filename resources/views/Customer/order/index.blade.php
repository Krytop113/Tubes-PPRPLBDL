@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Daftar Pesanan</h3>
                <p class="text-muted small">Kelola dan pantau status transaksi pelanggan Anda.</p>
            </div>
            {{-- <button class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fas fa-download me-1"></i> Export Laporan
            </button> --}}
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('orders.index') }}"
                class="btn rounded-pill px-4 btn-sm {{ !$status ? 'btn-dark' : 'btn-light border' }}">
                Semua
            </a>
            <a href="{{ route('orders.index', ['status' => 'pending']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-light border' }}">
                <i class="fas fa-clock me-1 text-warning"></i> Pending
            </a>
            <a href="{{ route('orders.index', ['status' => 'paid']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'paid' ? 'btn-info text-white' : 'btn-light border' }}">
                <i class="fas fa-check-circle me-1 text-info"></i> Paid
            </a>
            <a href="{{ route('orders.index', ['status' => 'order']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'order' ? 'btn-success' : 'btn-light border' }}">
                <i class="fas fa-box me-1 text-success"></i> Selesai
            </a>
            <a href="{{ route('orders.index', ['status' => 'cancel']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'cancel' ? 'btn-danger' : 'btn-light border' }}">
                <i class="fas fa-times-circle me-1 text-danger"></i> Cancel
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase fs-xs fw-bold text-muted">ID Order</th>
                            <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Tanggal & Waktu</th>
                            <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Total Pembayaran</th>
                            <th class="py-3 text-uppercase fs-xs fw-bold text-muted">Status</th>
                            <th class="pe-4 py-3 text-end text-uppercase fs-xs fw-bold text-muted">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span
                                            class="text-dark">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</span>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}
                                            WIB</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">
                                        Rp {{ number_format($order->total_raw, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
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
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-light btn-sm border shadow-sm">
                                        <i class="fas fa-eye me-1 text-muted"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center">
                                        <img src="https://illustrations.popsy.co/gray/box.svg" alt="Empty"
                                            style="width: 150px;" class="mb-3">
                                        <p class="text-muted">Belum ada pesanan yang masuk.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <style>
            .fs-xs {
                font-size: 0.75rem;
            }

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

            .table thead th {
                letter-spacing: 0.05em;
                border-bottom: 1px solid #f0f0f0;
            }

            .table tbody tr {
                transition: all 0.2s ease;
            }

            .table tbody tr:hover {
                background-color: #fcfcfc;
            }
        </style>
    @endsection
