@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="mb-5">
        <div id="heroCarousel" class="carousel slide carousel-fade shadow" data-bs-ride="carousel"
            style="border-radius: 0 0 50px 50px; overflow: hidden;">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active rounded-circle"
                    style="width: 12px; height: 12px;"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" class="rounded-circle"
                    style="width: 12px; height: 12px;"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" class="rounded-circle"
                    style="width: 12px; height: 12px;"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active" style="height: 550px;">
                    <img src="https://images.unsplash.com/photo-1532336414038-cf19250c5757?q=80&w=1200"
                        class="d-block w-100 h-100" style="object-fit: cover;">
                    <div class="carousel-caption d-flex h-100 align-items-center justify-content-center"
                        style="top: 0; left: 0; right: 0;">
                        <div class="bg-dark bg-opacity-50 p-5 rounded-4 w-75">
                            <h1 class="display-3 fw-bold">Kualitas Bumbu Terbaik</h1>
                            <p class="lead mb-4">Rasa otentik untuk masakan Anda, langsung dari bahan pilihan berkualitas
                                tinggi.</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('recipes.index') }}"
                                    class="btn btn-primary btn-lg rounded-pill px-4 shadow">Lihat Resep</a>
                                <a href="{{ route('ingredients.index') }}"
                                    class="btn btn-outline-light btn-lg rounded-pill px-4">Cari Bahan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-item" style="height: 550px;">
                    <img src="https://images.unsplash.com/photo-1518843875459-f738682238a6?q=80&w=1200"
                        class="d-block w-100 h-100" style="object-fit: cover;">
                    <div class="carousel-caption d-flex h-100 align-items-center justify-content-center"
                        style="top: 0; left: 0; right: 0;">
                        <div class="bg-dark bg-opacity-50 p-5 rounded-4 w-75">
                            <h1 class="display-3 fw-bold">Bahan Baku Segar</h1>
                            <p class="lead mb-4">Dipetik langsung dari petani lokal untuk menjaga kualitas gizi keluarga
                                Anda.</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('recipes.index') }}"
                                    class="btn btn-primary btn-lg rounded-pill px-4 shadow">Lihat Resep</a>
                                <a href="{{ route('ingredients.index') }}"
                                    class="btn btn-outline-light btn-lg rounded-pill px-4">Cari Bahan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-item" style="height: 550px;">
                    <img src="https://images.unsplash.com/photo-1547592166-23ac45744acd?q=80&w=1200"
                        class="d-block w-100 h-100" style="object-fit: cover;">
                    <div class="carousel-caption d-flex h-100 align-items-center justify-content-center"
                        style="top: 0; left: 0; right: 0;">
                        <div class="bg-dark bg-opacity-50 p-5 rounded-4 w-75">
                            <h1 class="display-3 fw-bold">Masak Jadi Mudah</h1>
                            <p class="lead mb-4">Ikuti panduan langkah demi langkah untuk menyajikan hidangan restoran di
                                rumah.</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('recipes.index') }}"
                                    class="btn btn-primary btn-lg rounded-pill px-4 shadow">Lihat Resep</a>
                                <a href="{{ route('ingredients.index') }}"
                                    class="btn btn-outline-light btn-lg rounded-pill px-4">Cari Bahan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
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
                        <img src="{{ asset('recipes/' . $recipe->image_url) }}" class="card-img-top"
                            style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold">{{ $recipe->name }}</h5>
                            <p class="text-muted small">{{ Str::limit($recipe->description, 60) }}</p>
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
                    <div class="card h-100 border-0 shadow-sm text-center p-3 hover-top" style="border-radius: 20px;">
                        <img src="{{ asset('ingredients/' . $ingredient->image_url) }}" class="mx-auto mb-3"
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
