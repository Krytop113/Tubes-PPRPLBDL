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
                <p class="text-muted mb-0">Inspirasi masak lezat untuk keluarga</p>
            </div>
            <a href="{{ route('recipes.index') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-bold">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach ($recipes->take(3) as $recipe)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-top card-custom">
                        <div class="position-relative">
                            <img src="{{ asset('recipes/' . $recipe->image_url) }}" class="card-img-top"
                                style="height: 240px; object-fit: cover;">
                        </div>
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold mb-2">{{ $recipe->name }}</h5>
                            <p class="text-muted small text-truncate-2">{{ Str::limit($recipe->description, 80) }}</p>
                            <a href="{{ route('recipes.show', $recipe->id) }}"
                                class="btn btn-outline-primary w-100 rounded-pill fw-semibold mt-2">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="ingredients" class="container mb-5 pb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">Bahan Baku Segar</h2>
                <p class="text-muted mb-0">Kualitas premium langsung ke dapur Anda</p>
            </div>
            <a href="{{ route('ingredients.index') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-bold">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach ($ingredients->take(4) as $ingredient)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-top card-custom">
                        <div class="p-3">
                            <img src="{{ asset('ingredients/' . $ingredient->image_url) }}" class="card-img-top mx-auto"
                                style="height: 160px; object-fit: contain;" alt="{{ $ingredient->name }}">
                        </div>
                        <div class="card-body p-4 text-center pt-0">
                            <h6 class="fw-bold mb-2">{{ $ingredient->name }}</h6>
                            <div class="text-primary fw-bold mb-3">
                                Rp {{ number_format($ingredient->price_per_unit, 0, ',', '.') }}
                                <span class="text-muted small fw-normal">/ {{ $ingredient->unit ?? 'Unit' }}</span>
                            </div>
                            <a href="{{ route('ingredients.show', $ingredient->id) }}"
                                class="btn btn-outline-primary w-100 rounded-pill">Tambah</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        .card-custom {
            transition: all 0.3s ease-in-out;
            border-radius: 15px !important;
        }

        .hover-top:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .rounded-5 {
            border-radius: 2rem !important;
        }

        .shadow-xs {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
