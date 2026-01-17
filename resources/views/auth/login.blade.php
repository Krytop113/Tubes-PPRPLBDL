@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden"
                    style="border: 1px solid var(--border-color) !important;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('favicon.ico') }}" alt="Logo" height="45" class="mb-3">
                            <h4 class="fw-bold text-dark">Selamat Datang</h4>
                            <p class="text-muted small">Masuk ke akun KriukKriuk Anda</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Email Address</label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-2 fs-6"
                                    placeholder="nama@email.com" required style="border: 1px solid #dee2e6 !important;">
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label small fw-bold text-secondary">Password</label>
                                    <a href="#" class="text-decoration-none small text-primary">Lupa?</a>
                                </div>
                                <input type="password" name="password" class="form-control bg-light border-0 py-2 fs-6"
                                    placeholder="••••••••" required style="border: 1px solid #dee2e6 !important;">
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label small text-muted" for="remember">Ingat saya di perangkat
                                    ini</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                                Masuk Sekarang
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="small text-muted">Belum punya akun?
                                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar
                                    Gratis</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
