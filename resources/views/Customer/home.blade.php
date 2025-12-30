@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="py-5 mb-5 bg-light" style="border-radius: 0 0 50px 50px;">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold mb-3">Kualitas Bumbu Terbaik</h1>
            <p class="lead text-muted mb-4">Rasa otentik untuk masakan Anda, langsung dari bahan pilihan berkualitas tinggi.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#recipes" class="btn btn-primary btn-lg rounded-pill px-4">Lihat Resep</a>
                <a href="{{ route('ingredients.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">Cari
                    Bahan</a>
            </div>
        </div>
    </section>

    <section id="recipes" class="container mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">Resep Populer</h2>
                <p class="text-muted mb-0">Inspirasi masak hari ini</p>
            </div>
            <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
        </div>

        <div class="row g-4">
            @foreach ($recipes->take(3) as $recipe)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-top" style="border-radius: 20px; overflow: hidden;">
                        <img src="{{ asset('storage/' . $recipe->image_url) }}" class="card-img-top"
                            style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold">{{ $recipe->name }}</h5>
                            <p class="text-muted small">{{ Str::limit($recipe->steps, 60) }}</p>
                            <a href="{{ route('recipes.show', $recipe->id) }}"
                                class="btn btn-light w-100 rounded-pill fw-semibold">Detail Resep</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="ingredients" class="container mb-5 pb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">Bahan Baku</h2>
                <p class="text-muted mb-0">Bahan segar langsung diantar</p>
            </div>
            <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat
                Semua</a>
        </div>

        <div class="row g-4">
            @foreach ($ingredients as $ingredient)
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-3" style="border-radius: 20px;">
                        <img src="{{ asset('storage/' . $ingredient->image_url) }}" class="mx-auto mb-3"
                            style="width: 120px; height: 120px; object-fit: contain;">
                        <h6 class="fw-bold mb-1">{{ $ingredient->name }}</h6>
                        <p class="text-primary fw-bold mb-3">Rp
                            {{ number_format($ingredient->price_per_unit, 0, ',', '.') }}</p>
                        <a href="{{ route('ingredients.show', $ingredient->id) }}"
                            class="btn btn-outline-secondary btn-sm rounded-pill">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        .hover-top:hover {
            transform: translateY(-10px);
            transition: 0.3s ease;
        }
    </style>
@endsection
