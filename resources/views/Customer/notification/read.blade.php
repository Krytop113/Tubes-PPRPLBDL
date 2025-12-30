@extends('layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('notification.index') }}"
                                class="text-decoration-none">Notifikasi</a></li>
                        <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">{{ $notification->title }}
                        </li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-bell-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">{{ $notification->title }}</h4>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $notification->date->format('d M Y') }}
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-clock me-1"></i> {{ $notification->date->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h6 class="text-uppercase fw-bold text-primary small mb-2" style="letter-spacing: 1px;">Subjek
                            </h6>
                            <p class="fs-5 fw-medium text-dark">{{ $notification->subject }}</p>
                        </div>

                        <hr class="opacity-50">

                        <div class="my-4">
                            <h6 class="text-uppercase fw-bold text-muted small mb-3" style="letter-spacing: 1px;">Pesan</h6>
                            <div class="text-secondary" style="line-height: 1.8; white-space: pre-line;">
                                {{ $notification->message }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                            <a href="{{ route('notification.index') }}" class="btn btn-light px-4 rounded-pill fw-bold">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>

                            <form method="POST" action="{{ route('notification.delete', $notification->id) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger px-4 rounded-pill fw-bold">
                                    <i class="bi bi-trash3 me-2"></i>Hapus Notifikasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
