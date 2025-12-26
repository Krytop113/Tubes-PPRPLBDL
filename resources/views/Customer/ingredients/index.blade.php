@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 d-flex justify-content-center">Bahan Baku</h1>

        <form method="GET" action="{{ route('ingredients.index') }}" class="mb-4">

            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search ingredients..."
                        value="{{ request('search') }}">
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-center">

                @foreach ($categories as $category)
                    @php
                        $checked = in_array($category->id, (array) request('categories'));
                    @endphp

                    <label class="btn {{ $checked ? 'btn-primary' : 'btn-outline-primary' }}">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="d-none"
                            {{ $checked ? 'checked' : '' }} onchange="this.form.submit()">
                        {{ $category->name }}
                    </label>
                @endforeach

                @if (request()->has('categories') || request()->has('search'))
                    <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="row">
            @forelse($ingredients as $ingredient)
                <div class="col-md-3 mb-3">
                    <div class="card h-100 shadow-sm">

                        <span class="badge bg-secondary bg-gradient m-2 align-auto">
                            {{ $ingredient->ingredient_category->name }}
                        </span>

                        <img src="{{ asset('ingredients/' . $ingredient->image_url) }}" class="card-img-top"
                            style="height:200px; object-fit:cover;" alt="{{ $ingredient->name }}">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $ingredient->name }}</h5>

                            <p class="text-muted">
                                Stock: {{ $ingredient->stock_quantity }} {{ $ingredient->unit }}
                            </p>

                            <a href="{{ route('ingredients.show', $ingredient->id) }}" class="btn btn-primary mt-auto">
                                View Details
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <p class="text-center">No ingredients found.</p>
            @endforelse
        </div>
    </div>
@endsection
