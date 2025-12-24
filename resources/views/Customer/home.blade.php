@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    <section class="hero">
        <div class="hero-content text-center">
            <h1>Kualitas Bumbu Terbaik</h1>
            <p>Rasa otentik untuk masakan Anda, langsung dari bahan pilihan.</p>

            <div class="mt-4">
                <a href="#ingredients" class="btn btn-primary">Lihat Produk</a>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Login</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Dashboard</a>
                @endguest
            </div>
        </div>
    </section>

    <section id="ingredients" class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Bahan Pilihan</h2>
            @auth
                <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Selengkapnya
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Selengkapnya
                </a>
            @endauth
        </div>

        <div class="row">
            @foreach ($ingredients as $ingredient)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="{{ asset($ingredient->image) }}" class="card-img-top" alt="{{ $ingredient->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $ingredient->name }}</h5>
                            <p class="card-text">
                                Rp {{ number_format($ingredient->price, 0, ',', '.') }} / {{ $ingredient->unit }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="recipes" class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Resep Populer</h2>
            @auth
                <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Selengkapnya
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Selengkapnya
                </a>
            @endauth
        </div>

        <div class="row">
            @foreach ($recipes as $recipe)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="{{ asset($recipe->image) }}" class="card-img-top" alt="{{ $recipe->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $recipe->title }}</h5>
                            <p class="card-text">
                                {{ Str::limit($recipe->description, 80) }}
                            </p>
                            <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-sm btn-primary">
                                Lihat Resep
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

@endsection
