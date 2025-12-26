<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KriukKriuk')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="d-flex flex-column min-vh-100">
    <div id="app" class="d-flex flex-column flex-grow-1">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('favicon.ico') }}" alt="KriukKriuk Logo" height="32">
                    <span class="fw-bold">KriukKriuk</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ingredients.index') }}">
                                    Bahan Baku
                                </a>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link disabled">|</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('recipes.index') }}">
                                    Resep
                                </a>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link disabled">|</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('notification.index') }}">
                                    <i class="bi bi-bell fs-5"></i>
                                </a>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link disabled">|</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-1" href="{{ route('cart.index') }}">
                                    <i class="bi bi-cart fs-5"></i>
                                    <span>Keranjang</span>
                                </a>
                            </li>
                        @endauth

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        Orders
                                    </a>

                                    <a class="dropdown-item" href="{{ route('editProfile') }}">
                                        Profile
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest

                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-grow-1 py-4">
            @yield('content')
        </main>

        <footer class="bg-dark text-light">
            <div class="container py-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6 class="fw-bold">Tentang Kami</h6>
                        <p class="text-light small mb-0">
                            Penyedia bumbu, daging, dan bahan pangan berkualitas sejak 1990.
                            Kami memastikan kesegaran bahan untuk dapur Anda.
                        </p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <h6 class="fw-bold">Link Cepat</h6>
                        <ul class="list-unstyled small">
                            <li>
                                <a href="{{ route('ingredients.index') }}" class="text-light">
                                    Belanja Bahan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('recipes.index') }}" class="text-light">
                                    Cari Resep
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cart.index') }}" class="text-light">
                                    Cek Keranjang
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 mb-3">
                        <h6 class="fw-bold">Kontak</h6>
                        <p class="text-light small mb-1">Jl. Industri No. 123, Jakarta</p>
                        <p class="text-light small mb-1">0812-3456-7890</p>
                        <p class="text-light small mb-0">info@kriukkriuk.id</p>
                    </div>

                </div>
            </div>

            <div class="text-center bg-dark py-2">
                <small class="text-light">
                    © {{ date('Y') }} <strong>KriukKriuk</strong>. All Rights Reserved.
                </small>
            </div>
        </footer>
    </div>
</body>

</html>
