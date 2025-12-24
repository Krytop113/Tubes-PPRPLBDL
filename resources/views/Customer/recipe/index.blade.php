@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 d-flex justify-content-center">Resep Menu</h1>

        {{-- FILTER FORM --}}
        <form method="GET" action="{{ route('recipes.index') }}" class="mb-4">

            {{-- SEARCH --}}
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search recipes..."
                        value="{{ request('search') }}">
                </div>
            </div>

            {{-- CATEGORY BUTTONS --}}
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

                {{-- RESET --}}
                @if (request()->has('categories') || request()->has('search'))
                    <a href="{{ route('recipes.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- RECIPE CARDS --}}
        <div class="row">
            @forelse($recipes as $recipe)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">

                        <span class="badge bg-secondary bg-gradient m-2 align-auto">
                            {{ $recipe->recipe_category->name }}
                        </span>

                        <img src="{{ asset('storage/' . $recipe->image_url) }}" class="card-img-top"
                            style="height:200px; object-fit:cover;" alt="{{ $recipe->name }}">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $recipe->name }}</h5>

                            <p class="text-muted">
                                Waktu Masak: {{ $recipe->cook_time }} menit
                            </p>

                            <p class="text-muted">
                                Serving: {{ $recipe->serving }} porsi
                            </p>

                            <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary mt-auto">
                                View Details
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <p class="text-center">No recipes found.</p>
            @endforelse
        </div>
    </div>
@endsection
