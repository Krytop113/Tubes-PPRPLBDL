@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('ingredients.index') }}" class="text-decoration-none">Bahan
                        Baku</a></li>
                <li class="breadcrumb-item active">{{ $ingredient->name }}</li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-5">
            <div class="col-lg-5">
                <img src="{{ asset('ingredients/' . $ingredient->image_url) }}" class="img-fluid shadow-sm mb-4"
                    style="border-radius: 20px; width: 100%; height: 350px; object-fit: contain; background-color: #f8f9fa;">

                <div class="bg-light p-4" style="border-radius: 20px;">
                    <h5 class="fw-bold mb-3">Informasi Bahan</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex justify-content-between border-bottom pb-2 text-secondary">
                            <span>Kategori</span>
                            <span class="fw-bold text-dark">{{ $ingredient->ingredient_category->name }}</span>
                        </li>
                        <li class="mb-2 d-flex justify-content-between border-bottom pb-2 text-secondary">
                            <span>Stok Tersedia</span>
                            <span class="fw-bold text-dark">{{ $ingredient->stock_quantity }} {{ $ingredient->unit }}</span>
                        </li>
                        <li class="d-flex justify-content-between text-secondary pt-1">
                            <span>Ditambahkan</span>
                            <span class="small">{{ $ingredient->created_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-7">
                <h1 class="fw-bold display-5 mb-3">{{ $ingredient->name }}</h1>

                <div class="d-flex gap-4 mb-4">
                    <div class="text-center bg-white border rounded px-4 py-2 shadow-sm">
                        <small class="text-muted d-block">Harga per {{ $ingredient->unit }}</small>
                        <span class="fw-bold text-primary fs-5">Rp
                            {{ number_format($ingredient->price_per_unit, 0, ',', '.') }}</span>
                    </div>
                </div>

                <h5 class="fw-bold">Deskripsi</h5>
                <p class="text-secondary mb-5" style="white-space: pre-line; line-height: 1.8;">
                    {{ $ingredient->description ?? 'Tidak ada deskripsi untuk bahan ini.' }}
                </p>

                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-1">Beli Eceran</h5>
                        <p class="small text-muted mb-4">Tentukan jumlah yang ingin Anda beli</p>

                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $ingredient->id }}">
                            <input type="hidden" name="price" value="{{ $ingredient->price_per_unit }}">

                            <div class="d-flex align-items-center justify-content-center mb-4 gap-3">
                                <button type="button" class="btn btn-outline-secondary rounded-circle"
                                    onclick="changeQty(-1)" style="width: 45px; height: 45px;">-</button>

                                <input type="number" id="qty_display" name="quantity" value="1" min="1"
                                    max="{{ $ingredient->stock_quantity }}"
                                    class="form-control text-center fw-bold border-0 fs-4"
                                    style="width: 80px; cursor: default" readonly>

                                <button type="button" class="btn btn-primary rounded-circle" onclick="changeQty(1)"
                                    style="width: 45px; height: 45px;">+</button>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow">
                                Masukkan Keranjang <i class="bi bi-cart-plus ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeQty(dir) {
            const input = document.getElementById('qty_display');
            let currentVal = parseInt(input.value);
            const maxVal = parseInt(input.getAttribute('max'));

            if (dir === 1) {
                if (currentVal < maxVal) input.value = currentVal + 1;
            } else {
                if (currentVal > 1) input.value = currentVal - 1;
            }
        }
    </script>
@endsection
