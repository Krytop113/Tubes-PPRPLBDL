@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Daftar Pesanan</h3>
                <p class="text-muted small">Kelola dan pantau status transaksi pelanggan Anda.</p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('orders.index') }}"
                class="btn rounded-pill px-4 btn-sm {{ !$status ? 'btn-dark' : 'btn-light border' }}">
                Semua
            </a>
            <a href="{{ route('orders.index', ['status' => 'pending']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'pending' ? 'btn-warning text-white' : 'btn-light border' }}">
                <i class="fas fa-clock me-1"></i> Pending
            </a>
            <a href="{{ route('orders.index', ['status' => 'paid']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'paid' ? 'btn-info text-white' : 'btn-light border' }}">
                <i class="fas fa-check-circle me-1"></i> Paid
            </a>
            <a href="{{ route('orders.index', ['status' => 'order']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'order' ? 'btn-success text-white' : 'btn-light border' }}">
                <i class="fas fa-box me-1"></i> Done
            </a>
            <a href="{{ route('orders.index', ['status' => 'cancel']) }}"
                class="btn rounded-pill px-4 btn-sm {{ $status == 'cancel' ? 'btn-danger text-white' : 'btn-light border' }}">
                <i class="fas fa-times-circle me-1"></i> Cancel
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
                        @forelse ($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">#{{ $order->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $order->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }} WIB</small>
                                    </div>
                                </td>
                                <td class="fw-bold text-primary">
                                    Rp {{ number_format($order->total_raw, 0, ',', '.') }}
                                </td>
                                <td>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge rounded-pill bg-warning-subtle px-3">Pending</span>
                                        @break

                                        @case('paid')
                                            <span class="badge rounded-pill bg-info-subtle px-3">Paid</span>
                                        @break

                                        @case('done')
                                            <span class="badge rounded-pill bg-success-subtle px-3">Done</span>
                                        @break

                                        @case('cancel')
                                            <span class="badge rounded-pill bg-danger-subtle px-3">Cancel</span>
                                        @break

                                        @default
                                            <span class="badge rounded-pill bg-secondary px-3">Unknown</span>
                                    @endswitch
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if ($order->status === 'paid')
                                            <button type="button" class="btn btn-success btn-sm shadow-sm btn-complete"
                                                data-id="{{ $order->id }}">
                                                <i class="fas fa-check"></i>
                                                <span class="d-none d-md-inline ms-1">Mark as Done</span>
                                            </button>

                                            <form id="done-form-{{ $order->id }}"
                                                action="{{ route('orders.complete', $order->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                        @endif
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="btn btn-light btn-sm border shadow-sm">
                                            <i class="fas fa-eye text-muted"></i>
                                            <span class="d-none d-md-inline ms-1 text-muted">Detail</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted">Belum ada pesanan.</p>
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
                background-color: #fff3cd;
                color: #856404;
            }

            .bg-info-subtle {
                background-color: #cff4fc;
                color: #055160;
            }

            .bg-success-subtle {
                background-color: #d1e7dd;
                color: #0f5132;
            }

            .bg-danger-subtle {
                background-color: #f8d7da;
                color: #842029;
            }

            .table thead th {
                letter-spacing: .05em;
                border-bottom: 1px solid #f0f0f0;
            }

            .table tbody tr:hover {
                background-color: #fcfcfc;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const completeButtons = document.querySelectorAll('.btn-complete');

                completeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-id');
                        const targetForm = document.getElementById('done-form-' + orderId);

                        Swal.fire({
                            title: 'Konfirmasi Pesanan',
                            text: "Apakah pesanan sudah selesai?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Selesaikan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-4',
                                confirmButton: 'rounded-3 px-4',
                                cancelButton: 'rounded-3 px-4'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (targetForm) {
                                    targetForm.submit();
                                } else {
                                    console.error("Form tidak ditemukan untuk ID: " + orderId);
                                }
                            }
                        });
                    });
                });
            });
        </script>
    @endsection
