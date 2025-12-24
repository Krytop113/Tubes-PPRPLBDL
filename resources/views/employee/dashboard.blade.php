@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<section class="hero">
    <div class="hero-content">
        <h1>Employee MEMEK</h1>
        <p>Rasa otentik untuk masakan Anda. Bergabunglah dengan ribuan pelanggan kami.</p>

        <div style="margin-top:20px">
            <a href="#products" class="btn">Lihat Produk</a>

            @guest
                <a href="{{ route('login') }}" class="btn">Login</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
            @endguest
        </div>
    </div>
</section>

<section id="products">
    <h2>Katalog Produk</h2>
    <div id="product-container">
        <p>Memuat produk...</p>
    </div>
</section>

@auth
<section class="container" style="margin-top:40px">
    <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
    </div>
</section>
@endauth

@endsection
