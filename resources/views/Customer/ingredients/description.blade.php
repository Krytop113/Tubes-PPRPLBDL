@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('ingredients.index') }}" class="btn btn-secondary mb-3">
        ← Back
    </a>

    <div class="row">
        <div class="col-md-6">
            <img 
                src="{{ asset('storage/' . $ingredient->image_url) }}" 
                class="img-fluid rounded shadow"
                alt="{{ $ingredient->name }}"
            >
        </div>

        <div class="col-md-6">
            <h2>{{ $ingredient->name }}</h2>

            <p class="text-muted">
                Stock: {{ $ingredient->stock }} {{ $ingredient->unit }}
            </p>

            <hr>

            <h5>Description</h5>
            <p>{{ $ingredient->description }}</p>

            <hr>

            <small class="text-muted">
                Created at: {{ $ingredient->created_at->format('d M Y') }}
            </small>
        </div>
    </div>
</div>
@endsection
