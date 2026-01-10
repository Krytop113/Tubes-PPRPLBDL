@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('recipes.index') }}" class="text-decoration-none">Resep</a></li>
                <li class="breadcrumb-item active">{{ $recipe->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-5">
                <img src="{{ asset('recipes/' . $recipe->image_url) }}" class="img-fluid shadow-sm mb-4"
                    style="border-radius: 20px; width: 100%; height: 350px; object-fit: cover;">

                <div class="bg-light p-4" style="border-radius: 20px;">
                    <h5 class="fw-bold mb-3">Bahan-bahan</h5>
                    <ul class="list-unstyled">
                        @foreach ($recipe->ingredients as $ingredient)
                            <li class="mb-2 d-flex justify-content-between border-bottom pb-2 text-secondary">
                                <span>{{ $ingredient->name }}</span>
                                <span class="fw-bold">{{ $ingredient->pivot->quantity_required }}
                                    {{ $ingredient->unit }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-lg-7">
                <h1 class="fw-bold display-5 mb-3">{{ $recipe->name }}</h1>
                <div class="d-flex gap-4 mb-4">
                    <div class="text-center bg-white border rounded px-3 py-2">
                        <small class="text-muted d-block">Waktu</small>
                        <span class="fw-bold text-primary">{{ $recipe->cook_time }} Min</span>
                    </div>
                    <div class="text-center bg-white border rounded px-3 py-2">
                        <small class="text-muted d-block">Porsi</small>
                        <span class="fw-bold text-primary">{{ $recipe->serving }} Porsi</span>
                    </div>
                </div>

                <h5 class="fw-bold">Deskripsi</h5>
                <p class="text-secondary mb-5" style="white-space: pre-line; line-height: 1.8">{{ $recipe->description }}</p>

                <h5 class="fw-bold">Cara Memasak</h5>
                <p class="text-secondary mb-5" style="white-space: pre-line; line-height: 1.8;">{{ $recipe->steps }}</p>

                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-1">Beli Paket Bahan</h5>
                        <p class="small text-muted mb-4">Atur jumlah porsi yang ingin dipesan</p>

                        <form action="{{ route('cart.addRecipe', $recipe->id) }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center justify-content-center mb-4 gap-3">
                                <button type="button" class="btn btn-outline-secondary rounded-circle"
                                    onclick="changeServing(-1)" style="width: 45px; height: 45px;">-</button>
                                <input type="number" id="serving_display" name="serving_order"
                                    value="{{ $recipe->serving }}" class="form-control text-center fw-bold border-0 fs-4"
                                    style="width: 80px;" readonly>
                                <button type="button" class="btn btn-primary rounded-circle" onclick="changeServing(1)"
                                    style="width: 45px; height: 45px;">+</button>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow">
                                Tambah ke Keranjang <i class="bi bi-cart-plus ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseServing = {{ $recipe->serving }};
        let currentServing = baseServing;

        function changeServing(dir) {
            if (dir === 1) currentServing += baseServing;
            else if (currentServing > baseServing) currentServing -= baseServing;
            document.getElementById('serving_display').value = currentServing;
        }
    </script>
@endsection
