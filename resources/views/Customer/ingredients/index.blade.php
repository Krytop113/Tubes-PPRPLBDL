@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Bahan Baku Segar</h2>
            <p class="text-muted">Lengkapi kebutuhan dapur Anda dengan bahan berkualitas.</p>
        </div>

        <form method="GET" action="{{ route('ingredients.index') }}" class="mb-5" id="filterForm">
            <div class="row justify-content-center g-2">
                <div class="col-md-6">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-2"
                            placeholder="Cari bahan (misal: Ayam, Cabai...)" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 d-flex flex-wrap gap-2 justify-content-center mt-3">
                    <a href="{{ route('ingredients.index', ['search' => request('search')]) }}"
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
            @forelse($ingredients as $ingredient)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-top" style="transition: all 0.3s; border-radius: 15px;">
                        <div class="position-relative">
                            <img src="{{ asset('ingredients/' . $ingredient->image_url) }}" class="card-img-top"
                                style="height:200px; object-fit:cover;" alt="{{ $ingredient->name }}">

                            <span class="position-absolute top-0 start-0 m-3 badge bg-white text-dark shadow-sm">
                                {{ $ingredient->ingredient_category->name }}
                            </span>
                        </div>
                        <div class="card-body p-4 text-center">
                            <h5 class="card-title fw-bold mb-2">{{ $ingredient->name }}</h5>
                            <div class="d-flex justify-content-center text-primary small fw-bold mb-3">
                                <span>Rp {{ number_format($ingredient->price_per_unit, 0, ',', '.') }} /
                                    {{ $ingredient->unit }}</span>
                            </div>

                            @if ($ingredient->stock_quantity > 0)
                                <a href="{{ route('ingredients.show', $ingredient->id) }}"
                                    class="btn btn-outline-primary w-100 rounded-pill">
                                    Tambah
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 rounded-pill" disabled>
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-x-circle display-1 text-muted opacity-25"></i>
                    <h5 class="text-muted">Bahan tidak ditemukan.</h5>
                    <p class="small text-muted">Coba gunakan kata kunci lain atau pilih kategori berbeda.</p>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-5">
                {!! $ingredients->links('pagination::bootstrap-5') !!}
            </div>
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
