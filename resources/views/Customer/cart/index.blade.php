@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">Keranjang Saya</h3>

        @if ($items->isEmpty())
            <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" style="width:150px; opacity:0.5;">
                <p class="mt-4 text-muted fs-5">
                    Wah, keranjangmu masih kosong nih.
                </p>
                <a href="{{ route('recipes.index') }}" class="btn btn-primary px-4 rounded-pill">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="table-responsive p-3">
                            <table class="table align-middle">
                                <thead class="text-muted small text-uppercase">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $id => $item)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $item->name }}</span>
                                            </td>

                                            <td>
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="decrease">
                                                        <button class="btn btn-sm btn-light border"
                                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                            −
                                                        </button>
                                                    </form>

                                                    <span class="fw-bold px-2">{{ $item->quantity }}</span>

                                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="increase">
                                                        <button class="btn btn-sm btn-light border">+</button>
                                                    </form>
                                                </div>
                                            </td>

                                            <td class="fw-bold text-primary">
                                                Rp {{ number_format(session('cart_total', 0), 0, ',', '.') }}
                                            </td>

                                            <td class="text-end">
                                                <button class="btn btn-sm text-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-url="{{ route('cart.deleteItem', $id) }}"
                                                    data-name="{{ $item->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4" style="border-radius:15px;">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Harga</span>
                            <span class="fw-bold fs-5 text-primary">
                                Rp {{ number_format(session('cart_total', 0), 0, ',', '.') }}
                            </span>
                        </div>

                        <hr>

                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill">
                                Checkout Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Yakin ingin menghapus
                        <strong id="deleteItemName"></strong>
                        dari keranjang?
                    </p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Hapus
                        </button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-url');
            const name = button.getAttribute('data-name');

            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteForm').action = url;
        });
    </script>
@endsection
