@extends('layouts.app')

@section('content')
    <div class="container">

        <h3 class="mb-4">Daftar Pesanan</h3>

        <div class="mb-3">
            <a href="{{ route('orders.index') }}" class="btn btn-sm {{ !$status ? 'btn-dark' : 'btn-outline-dark' }}">
                Semua
            </a>

            <a href="{{ route('orders.index', ['status' => 'pending']) }}"
                class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                Pending
            </a>

            <a href="{{ route('orders.index', ['status' => 'paid']) }}"
                class="btn btn-sm {{ $status == 'paid' ? 'btn-info' : 'btn-outline-info' }}">
                Paid
            </a>

            <a href="{{ route('orders.index', ['status' => 'order']) }}"
                class="btn btn-sm {{ $status == 'order' ? 'btn-success' : 'btn-outline-success' }}">
                Selesai
            </a>

            <a href="{{ route('orders.index', ['status' => 'cancel']) }}"
                class="btn btn-sm {{ $status == 'cancel' ? 'btn-danger' : 'btn-outline-danger' }}">
                Cancel
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</td>
                                <td>
                                    Rp {{ number_format($order->total_raw, 0, ',', '.') }}
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada data order
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endsection
