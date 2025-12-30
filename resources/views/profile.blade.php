@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4 border-0 rounded-4">
                <h4 class="fw-bold mb-4 text-center">Edit Profil</h4>

                @if (session('success'))
                    <div class="alert alert-success small">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa fa-user text-muted"></i>
                            </span>
                            <input
                                type="text"
                                name="name"
                                class="form-control bg-light border-start-0"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa fa-envelope text-muted"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                class="form-control bg-light border-start-0"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                        </div>

                        @if ($user->email_verified_at)
                            <small class="text-success">
                                <i class="fa fa-circle-check"></i> Email terverifikasi
                            </small>
                        @else
                            <small class="text-danger">
                                <i class="fa fa-circle-xmark"></i> Email belum terverifikasi
                            </small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nomor Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa fa-phone text-muted"></i>
                            </span>
                            <input
                                type="text"
                                name="phone_number"
                                class="form-control bg-light border-start-0"
                                value="{{ old('phone_number', $user->phone_number) }}"
                            >
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa fa-calendar text-muted"></i>
                            </span>
                            <input
                                type="date"
                                name="date_of_birth"
                                class="form-control bg-light border-start-0"
                                value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}"
                            >
                        </div>
                    </div>

                    <hr class="opacity-25">

                    <a href="{{ route('password.request') }}"
                       class="btn btn-outline-primary w-100 mb-2 d-flex justify-content-between align-items-center py-2">
                        <span>
                            <i class="fa fa-lock me-2"></i> Ganti Password
                        </span>
                        <i class="fa fa-chevron-right small"></i>
                    </a>

                    {{-- @if (!$user->email_verified_at)
                        <form action="{{ route('verification.send') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-outline-success w-100 mb-3 d-flex justify-content-between align-items-center py-2">
                                <span>
                                    <i class="fa fa-envelope-circle-check me-2"></i> Verifikasi Email
                                </span>
                                <span class="badge bg-success">Kirim Email</span>
                            </button>
                        </form>
                    @endif --}}

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">
                        Simpan Perubahan
                    </button>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light text-danger w-100 fw-bold py-2 border">
                            <i class="fa fa-sign-out-alt me-2"></i> Keluar
                        </button>
                    </form>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
