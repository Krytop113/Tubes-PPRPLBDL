@extends('layouts.app')

@section('content')

<div class="container">
    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary mb-4 border-0">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Resep
    </a>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 15px;">
                <img src="{{ asset('storage/' . $recipe->image_url) }}" class="card-img p-0"
                    alt="{{ $recipe->name }}" style="height: 400px; object-fit: cover;">
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <h5 class="fw-bold mb-3"><i class="bi bi-basket2-fill text-primary me-2"></i>Bahan-bahan</h5>
                <ul class="list-group list-group-flush">
                    @forelse($recipe->ingredients as $ingredient)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span>{{ $ingredient->name }}</span>
                            <span class="badge bg-light text-dark border fw-semibold">
                                {{ $ingredient->pivot->quantity_required }} / {{ $ingredient->unit }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small px-0 bg-transparent">Bahan-bahan tidak tersedia.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="ps-lg-3">
                <h1 class="display-6 fw-bold text-dark">{{ $recipe->name }}</h1>
                <p class="text-muted mb-4">
                    <i class="bi bi-calendar3 me-1"></i> Diterbitkan: {{ $recipe->created_at->format('d M Y') }}
                </p>

                <div class="d-flex gap-5 my-4">
                    <div>
                        <span class="text-muted d-block small uppercase fw-bold">WAKTU MASAK</span>
                        <strong class="h5"><i class="bi bi-clock-history me-1 text-primary"></i> {{ $recipe->cook_time }} Menit</strong>
                    </div>
                    <div>
                        <span class="text-muted d-block small uppercase fw-bold">PORSI</span>
                        <strong class="h5"><i class="bi bi-people-fill me-1 text-primary"></i> {{ $recipe->serving }} Porsi</strong>
                    </div>
                </div>

                <hr class="opacity-10">

                <h5 class="fw-bold">Deskripsi</h5>
                <p class="text-secondary" style="line-height: 1.8;">
                    {{ $recipe->description }}
                </p>

                <hr class="opacity-10">

                <h5 class="fw-bold">Langkah-langkah</h5>
                <p class="text-secondary" style="line-height: 1.8;">
                    {{ $recipe->steps }}
                </p>

                <hr class="opacity-10">

                <div class="card border-0 bg-white shadow mt-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-1">Beli Bahan Masakan</h5>
                            <p class="small text-muted">Bahan akan dikalikan sesuai jumlah porsi</p>
                        </div>
                        
                        <form action="{{ route('cart.addRecipe', $recipe->id) }}" method="POST">
                            @csrf
                            <div class="serving-control d-flex align-items-center justify-content-center mb-4">
                                <button type="button" class="btn btn-outline-primary btn-circle shadow-sm" onclick="changeServing(-1)">
                                    <i class="bi bi-dash-lg"></i>
                                </button>

                                <div class="text-center mx-4">
                                    <input type="number" id="serving_display" name="serving_order" 
                                           value="{{ $recipe->serving }}" readonly>
                                </div>

                                <button type="button" class="btn btn-primary btn-circle shadow-sm" onclick="changeServing(1)">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                            
                            <div class="alert bg-light py-3 text-center small border-0 mb-4" style="border-radius: 12px;">
                                <i class="bi bi-box-seam me-2 text-primary"></i> 
                                Total Pesanan: <span id="multiplier_label" class="fw-bold text-primary">1</span> Paket Bahan Lengkap
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 btn-lg shadow fw-bold py-3" style="border-radius: 12px;">
                                <i class="bi bi-cart-plus-fill me-2"></i> Tambahkan ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const baseServing = {{ $recipe->serving }}; 
    let currentServing = baseServing;

    function changeServing(direction) {
        const input = document.getElementById('serving_display');
        const label = document.getElementById('multiplier_label');

        if (direction === 1) {
            currentServing += baseServing;
        } else {
            if (currentServing > baseServing) {
                currentServing -= baseServing;
            }
        }

        input.value = currentServing;
        label.innerText = currentServing / baseServing;
    }
</script>
@endsection