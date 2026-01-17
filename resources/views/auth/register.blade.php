@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4" style="border: 1px solid var(--border-color) !important;">
                    <div class="card-body p-5">
                        <h4 class="fw-bold text-dark mb-1">Buat Akun Baru</h4>
                        <p class="text-muted small mb-4">Bergabunglah dengan komunitas masak kami.</p>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control bg-light border-0 py-2"
                                        required style="border: 1px solid #dee2e6 !important;">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-secondary">Email</label>
                                    <input type="email" name="email" class="form-control bg-light border-0 py-2"
                                        required style="border: 1px solid #dee2e6 !important;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Password</label>
                                    <input type="password" name="password" class="form-control bg-light border-0 py-2"
                                        required style="border: 1px solid #dee2e6 !important;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Konfirmasi</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control bg-light border-0 py-2" required
                                        style="border: 1px solid #dee2e6 !important;">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                                    Daftarkan Akun
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4 text-muted small">
                            Sudah punya akun? <a href="{{ route('login') }}"
                                class="text-primary fw-bold text-decoration-none">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
