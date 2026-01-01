@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Eksplorasi Resep</h2>
            <p class="text-muted">Pilih resep favorit dan beli bahan bakunya secara instan.</p>
        </div>

        <form method="GET" action="{{ route('recipes.index') }}" class="mb-5">
            <div class="row justify-content-center g-3">
                <div class="col-md-6">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-2"
                            placeholder="Cari Resep Makanan..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 d-flex flex-wrap gap-2 justify-content-center mt-3">
                    <a href="{{ route('recipes.index', ['search' => request('search')]) }}"
                        class="btn btn-sm rounded-pill px-4 {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Semua
                    </a>

                    @foreach ($categories as $category)
                        <label
                            class="btn btn-sm rounded-pill px-4 {{ request('category') == $category->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                            <input type="radio" name="category" value="{{ $category->id }}" class="d-none"
                                onchange="this.form.submit()" {{ request('category') == $category->id ? 'checked' : '' }}>
                            {{ $category->name }}
                        </label>
                    @endforeach

                    @if (request()->has('category') || request()->has('search'))
                        <a href="{{ route('ingredients.index') }}"
                            class="btn btn-sm btn-link text-muted text-decoration-none">Reset Filter</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="row g-4">
            @forelse($recipes as $recipe)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-top card-recipe">
                        <div class="position-relative overflow-hidden">
                            <img src="{{ asset('recipes/' . $recipe->image_url) }}" class="card-img-top"
                                style="height:200px; object-fit:cover;" alt="{{ $recipe->name }}">

                            <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark shadow-sm fw-medium">
                                {{ $recipe->recipe_category->name }}
                            </span>
                        </div>

                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-2 text-truncate-2" style="height: 3rem;">
                                {{ $recipe->name }}
                            </h5>

                            <div class="d-flex text-muted small mb-3 gap-3">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-clock me-1 text-primary"></i> {{ $recipe->cook_time }}m
                                </span>
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-people me-1 text-primary"></i> {{ $recipe->serving }} Porsi
                                </span>
                            </div>

                            <a href="{{ route('recipes.show', $recipe->id) }}"
                                class="btn btn-outline-primary w-100 rounded-pill fw-bold py-2">
                                Lihat Resep
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
                    </div>
                    <h5 class="text-muted">Resep tidak ditemukan.</h5>
                    <p class="small text-muted">Coba gunakan kata kunci lain atau pilih kategori berbeda.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {!! $recipes->links('pagination::bootstrap-5') !!}
        </div>
    </div>

    <style>
        .hover-top {
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

        .pagination {
            gap: 5px;
        }

        .pagination .page-link {
            border: none;
            border-radius: 10px !important;
            padding: 10px 18px;
            color: #6c757d;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }

        .card-img-top {
            border-radius: 15px 15px 0 0;
            transition: transform 0.5s ease;
        }

        .card-recipe:hover .card-img-top {
            transform: scale(1.05);
        }
    </style>
@endsection
