@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('ingredients.index') }}" class="btn btn-secondary mb-3">
            ← Back
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $ingredient->image_url) }}" class="img-fluid rounded shadow"
                    alt="{{ $ingredient->name }}">
            </div>

            <div class="col-md-6">
                <h2>{{ $ingredient->name }}</h2>

                <p class="text-muted">
                    Stock: {{ $ingredient->stock_quantity }} {{ $ingredient->unit }}
                </p>

                <hr>

                <h5>Description</h5>
                <p>{{ $ingredient->description }}</p>

                <hr>

                <h5>Price per Unit</h5>
                <p>Rp {{ number_format($ingredient->price_per_unit, 0, ',', '.') }} per
                    {{ $ingredient->unit }}</p>

                <hr>

                <small class="text-muted">
                    Created at: {{ $ingredient->created_at->format('d M Y') }}
                </small>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $ingredient->id }}">
                    <input type="hidden" name="price" value="{{ $ingredient->price_per_unit }}">

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1"
                            min="1" max="{{ $ingredient->stock }}">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
