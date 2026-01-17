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

    <style>
        /* Memastikan chatbot tidak tertutup elemen footer pada layar kecil jika diperlukan */
        .flowise-chatbot-button {
            z-index: 9999 !important;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <div id="app" class="d-flex flex-column flex-grow-1">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('favicon.ico') }}" alt="KriukKriuk Logo" height="32">
                    <span class="fw-bold">KriukKriuk</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    {{-- Left Navbar --}}
                    <ul class="navbar-nav me-auto"></ul>

                    {{-- Right Navbar --}}
                    <ul class="navbar-nav ms-auto align-items-center">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ingredients.index') }}">Bahan Baku</a>
                            </li>
                            <li class="nav-item"><span class="nav-link disabled">|</span></li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('recipes.index') }}">Resep</a>
                            </li>
                            <li class="nav-item"><span class="nav-link disabled">|</span></li>
                            <li class="nav-item">
                                <a class="nav-link position-relative d-inline-block"
                                    href="{{ route('notification.index') }}">
                                    <i class="bi bi-bell fs-5"></i>

                                    @if (isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                                            style="margin-top: 15px; margin-left: -10px;">
                                            <span class="visually-hidden">New alerts</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item"><span class="nav-link disabled">|</span></li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-1" href="{{ route('cart.index') }}">
                                    <i class="bi bi-cart fs-5"></i>
                                    <span>Keranjang</span>
                                </a>
                            </li>
                        @endauth

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (Auth::user()->role->name == 'admin')
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">Control Dashboard</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">Orders</a>
                                    <a class="dropdown-item" href="{{ route('coupons.index') }}">Coupons</a>
                                    <a class="dropdown-item" href="{{ route('editProfile') }}">Profile</a>
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

        <main class="flex-grow-1 py-4 min-vh-100">
            @yield('content')
        </main>

        <footer class="footer-custom py-5 mt-auto">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <a class="navbar-brand fw-bold text-primary mb-3 d-block" href="/">
                            <i class="bi bi-cloud-check-fill me-2"></i>Kriuk<span class="text-dark">Kriuk</span>
                        </a>
                        <p class="small text-muted pe-lg-5">
                            Platform manajemen dapur modern untuk pecinta kuliner Indonesia. Kami membantu Anda
                            mengelola resep, bahan baku, dan inventaris secara efisien dalam satu tempat.
                        </p>
                        <div class="d-flex gap-3 mt-4">
                            <a href="#" class="text-secondary fs-5 hover-blue"><i
                                    class="bi bi-instagram"></i></a>
                            <a href="https://github.com/Krytop113" class="text-secondary fs-5 hover-blue"><i
                                    class="bi bi-github"></i></a>
                            <a href="#" class="text-secondary fs-5 hover-blue"><i
                                    class="bi bi-linkedin"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-6">
                        <h6 class="fw-bold text-dark mb-3 small text-uppercase tracking-wider">Fitur Utama</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="{{ route('recipes.index') }}"
                                    class="text-decoration-none text-muted hover-blue">Daftar Resep</a></li>
                            <li class="mb-2"><a href="{{ route('ingredients.index') }}"
                                    class="text-decoration-none text-muted hover-blue">Manajemen Bahan</a></li>
                            <li class="mb-2"><a href="{{ route('coupons.index') }}"
                                    class="text-decoration-none text-muted hover-blue">Kupon Belanja</a></li>
                            <li class="mb-2"><a href="{{ route('cart.index') }}"
                                    class="text-decoration-none text-muted hover-blue">Keranjang Saya</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 col-6">
                        <h6 class="fw-bold text-dark mb-3 small text-uppercase tracking-wider">Perusahaan</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="#"
                                    class="text-decoration-none text-muted hover-blue">Tentang Kami</a></li>
                            <li class="mb-2"><a href="#"
                                    class="text-decoration-none text-muted hover-blue">Karir</a></li>
                            <li class="mb-2"><a href="#"
                                    class="text-decoration-none text-muted hover-blue">Kebijakan Privasi</a></li>
                            <li class="mb-2"><a href="#"
                                    class="text-decoration-none text-muted hover-blue">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h6 class="fw-bold text-dark mb-3 small text-uppercase tracking-wider">Dukungan</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="#"
                                    class="text-decoration-none text-muted hover-blue">Pusat Bantuan</a></li>
                            <li class="mb-2"><a href="#"
                                    class="text-decoration-none text-muted hover-blue">Hubungi CS</a></li>
                            <li class="mb-2 text-muted"><i class="bi bi-geo-alt me-2"></i> Bandung, Jawa Barat</li>
                            <li class="mb-2 text-muted"><i class="bi bi-envelope me-2"></i> help@kriukkriuk.com</li>
                        </ul>
                    </div>
                </div>

                <hr class="my-4" style="border-top: 1px solid var(--border-color); opacity: 0.5;">

                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="small text-muted mb-0">
                            &copy; 2026 <strong>KriukKriuk System</strong>. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                        <span class="small text-muted">Dibuat dengan ❤️ oleh <a href="https://github.com/Krytop113"
                                class="text-decoration-none text-primary fw-bold">Javier, Gearald, Marco</a></span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @include('components.notification')

    <style>
        :root {
            --primary-blue: #0d6efd;
            --border-color: #e9ecef;
            --bg-light: #f4f7fa;
            --card-bg: #ffffff;
        }

        body {
            background-color: var(--bg-light);
            color: #495057;
            font-family: 'Nunito', sans-serif;
        }

        .navbar-custom {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
            padding: 0.8rem 0;
        }

        .footer-custom {
            background-color: #ffffff;
            border-top: 1px solid var(--border-color);
            color: #6c757d;
        }

        .btn-login {
            background-color: var(--primary-blue);
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }

        flowise-chatbot-button {
            transition: transform 0.3s ease-in-out !important;
            transform: translateX(30px);
        }

        flowise-chatbot-button:hover {
            transform: translateX(0);
        }
    </style>

    <script type="module">
        import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
        Chatbot.init({
            chatflowid: "efce586d-8618-4d4c-bfdf-40a6cc1736ff",
            apiHost: "https://cloud.flowiseai.com",

            welcomeMessage: "Halo! Selamat datang di KriukKriuk. Mau cari resep masakan Indonesia apa hari ini?",
            chatWindow: {
                welcomeMessage: "Halo! Selamat datang di KriukKriuk. Mau cari resep masakan Indonesia apa hari ini?",
                backgroundColor: "#ffffff",
                fontSize: 12,
                botMessage: {
                    backgroundColor: "#f7f8ff",
                    textColor: "#303235",
                    showAvatar: true,
                    avatarSrc: "https://raw.githubusercontent.com/walkxcode/dashboard-icons/main/svg/google-messages.svg",
                },
                userMessage: {
                    backgroundColor: "#ff9800",
                    textColor: "#ffffff",
                    showAvatar: true,
                },
                textInput: {
                    placeholder: "Ketik pertanyaan Anda di sini...",
                    sendButtonColor: "#ff9800",
                }
            },
            theme: {
                button: {
                    backgroundColor: "#ff9800",
                    right: 35,
                    bottom: 25,
                    size: "medium",
                    iconColor: "white",
                }
            }
        })
    </script>
</body>

</html>
