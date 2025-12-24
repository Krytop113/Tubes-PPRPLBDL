@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary mb-3">
            ← Back
        </a>

        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $recipe->image_url) }}" class="img-fluid rounded shadow"
                    alt="{{ $recipe->name }}">
            </div>

            <div class="col-md-6">
                <h2>{{ $recipe->name }}</h2>

                <hr>

                <h5>Description</h5>
                <p>{{ $recipe->description }}</p>

                <hr>

                <h5>Steps</h5>
                <p>{{ $recipe->steps }} {{ $recipe->unit }}</p>

                <hr>

                <h5>Waktu Masak</h5>
                <p>{{ $recipe->cook_time }} menit</p>

                <hr>

                <h5>Ukuran porsi </h5>
                <p>{{ $recipe->serving }} Porsi</p>

                <small class="text-muted">
                    Created at: {{ $recipe->created_at->format('d M Y') }}
                </small>
            </div>
        </div>
    </div>
@endsection
