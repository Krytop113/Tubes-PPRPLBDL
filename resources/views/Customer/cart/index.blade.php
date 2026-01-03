@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="container py-5">
        <h3 class="fw-bold mb-4">Keranjang Saya</h3>

        @if ($items->isEmpty())
            <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" style="width:150px; opacity:0.5;">
                <p class="mt-4 text-muted fs-5">Wah, keranjangmu masih kosong nih.</p>
                <a href="{{ route('recipes.index') }}" class="btn btn-primary px-4 rounded-pill">Mulai Belanja</a>
            </div>
        @else
            <form action="{{ route('cart.checkout') }}" method="POST" id="mainCheckoutForm">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm" style="border-radius:15px;">
                            <div class="table-responsive p-3">
                                <table class="table align-middle">
                                    <thead class="text-muted small text-uppercase">
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </th>
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
                                                    <input type="checkbox" name="selected_items[]"
                                                        value="{{ $id }}" class="form-check-input item-checkbox"
                                                        data-price="{{ $item->price }}" data-qty="{{ $item->quantity }}">
                                                </td>
                                                <td><span class="fw-bold">{{ $item->name }} ({{ $item->unit }})</span></td>
                                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <a href="{{ route('cart.update', ['id' => $id, 'action' => 'decrease']) }}"
                                                            class="btn btn-sm btn-light border {{ $item->quantity <= 1 ? 'disabled' : '' }}">-</a>
                                                        <span class="fw-bold px-2">{{ $item->quantity }}</span>
                                                        <a href="{{ route('cart.update', ['id' => $id, 'action' => 'increase']) }}"
                                                            class="btn btn-sm btn-light border">+</a>
                                                    </div>
                                                </td>
                                                <td class="fw-bold text-primary">
                                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm text-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
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
                        <div class="card border-0 shadow-sm p-4" style="border-radius:15px; position: sticky; top: 20px;">
                            <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total</span>
                                <span class="fw-bold fs-5 text-primary" id="grandTotalDisplay">Rp 0</span>
                            </div>
                            <hr>
                            <button type="button" id="checkoutBtn" class="btn btn-primary w-100 py-3 fw-bold rounded-pill"
                                data-bs-toggle="modal" data-bs-target="#confirmCheckoutModal" disabled>
                                Checkout Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="confirmCheckoutModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                            <div class="modal-body text-center p-5">
                                <div class="mb-4">
                                    <i class="bi bi-cart-check text-primary" style="font-size: 4rem;"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Konfirmasi Pesanan</h4>
                                <p class="text-muted mb-4">
                                    Apakah Anda yakin ingin memproses checkout untuk item yang dipilih?
                                    <br>Total pembayaran: <strong class="text-primary" id="modalTotalDisplay">Rp 0</strong>
                                </p>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">Ya, Proses
                                        Sekarang</button>
                                    <button type="button" class="btn btn-light btn-lg rounded-pill"
                                        data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="modal-body">
                        <p>Yakin ingin menghapus <strong id="deleteItemName"></strong> dari keranjang?</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalDisplay = document.getElementById('grandTotalDisplay');
        const modalTotalDisplay = document.getElementById('modalTotalDisplay');
        const checkoutBtn = document.getElementById('checkoutBtn');

        function updateSummary() {
            let total = 0;
            let count = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.price) * parseFloat(cb.dataset.qty);
                    count++;
                }
            });

            const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
            totalDisplay.textContent = formattedTotal;
            modalTotalDisplay.textContent = formattedTotal;
            checkoutBtn.disabled = count === 0;
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateSummary();
            });
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));

        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const btn = event.relatedTarget;
                document.getElementById('deleteItemName').textContent = btn.getAttribute('data-name');
                document.getElementById('deleteForm').action = btn.getAttribute('data-url');
            });
        }
    </script>
@endsection
