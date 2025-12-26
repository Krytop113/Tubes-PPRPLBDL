@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">Keranjang Belanja</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        @if (empty($items) || count($items) == 0)
            <div class="text-center py-5">
                <h4>Keranjang Anda masih kosong.</h4>
                <a href="{{ url('/') }}" class="btn btn-primary mt-3">Mulai Belanja</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th width="180">Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($items as $id => $item)
                            @php
                                $subtotal = $item->price * $item->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td><input type="checkbox" checked disabled></td>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                        </form>
                                        <span class="mx-3 fw-bold">{{ $item->quantity }}</span>
                                        <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                                        </form>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-url="{{ route('cart.deleteItem', $id) }}"
                                        data-name="{{ $item->name }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Total: <span class="text-danger">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </h4>
                    <div>
                        <a href="{{ url('/') }}" class="btn btn-outline-primary me-2">Lanjut Belanja</a>
                        <button type="button" class="btn btn-danger btn-lg px-5" data-bs-toggle="modal"
                            data-bs-target="#checkoutModal">
                            Checkout Sekarang
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus <strong id="productName"></strong> dari keranjang?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Pastikan semua pesanan sudah sesuai. Lanjutkan ke proses pembayaran?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cek Lagi</button>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success px-4">
                            Proses Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const url = button.getAttribute('data-url');
                const name = button.getAttribute('data-name');

                const form = deleteModal.querySelector('#deleteForm');
                const nameDisplay = deleteModal.querySelector('#productName');

                form.setAttribute('action', url);
                nameDisplay.textContent = name;
            });
        });
    </script>

@endsection
