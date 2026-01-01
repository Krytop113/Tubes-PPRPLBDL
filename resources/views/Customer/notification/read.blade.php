@extends('layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('notification.index') }}" class="text-primary text-decoration-none fw-medium">
                                Notifikasi
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">Detail</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 24px;">
                    <div class="card-header border-0 bg-white pt-5 px-5">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center gap-4">
                                <div class="bg-primary bg-opacity-10 p-4 rounded-4">
                                    <i class="bi bi-envelope-paper-fill text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold text-dark mb-1">{{ $notification->title }}</h3>
                                    <div class="d-flex align-items-center gap-3 text-muted small">
                                        <span><i class="bi bi-calendar3 me-1"></i>
                                            {{ $notification->date->format('d M Y') }}</span>
                                        <span><i class="bi bi-clock me-1"></i>
                                            {{ $notification->date->format('H:i') }}</span>
                                        <span
                                            class="badge {{ $notification->status === 'unread' ? 'bg-warning text-dark' : 'bg-light text-muted' }} rounded-pill px-3">
                                            {{ ucfirst($notification->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-5 pb-5">
                        <div class="bg-light p-4 rounded-4 mb-4 border-start border-primary border-4">
                            <h6 class="text-uppercase fw-bold text-primary small mb-2" style="letter-spacing: 1.5px;">Subjek
                            </h6>
                            <p class="fs-5 fw-semibold text-dark mb-0">{{ $notification->subject }}</p>
                        </div>

                        <div class="py-2">
                            <h6 class="text-uppercase fw-bold text-muted small mb-3" style="letter-spacing: 1.5px;">Pesan
                                Lengkap</h6>
                            <div class="text-dark fs-6" style="line-height: 1.8; white-space: pre-line;">
                                {{ $notification->message }}
                            </div>
                        </div>

                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-5 gap-3 pt-4 border-top">
                            <a href="{{ route('notification.index') }}"
                                class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold transition-all shadow-sm">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                            </a>

                            <button type="button" class="btn btn-danger px-4 py-2 rounded-pill fw-bold shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash3 me-2"></i>Hapus Notifikasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-body p-5 text-center">
                    <div class="text-danger mb-4">
                        <i class="bi bi-exclamation-octagon fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Konfirmasi Hapus</h4>
                    <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus notifikasi ini? Tindakan ini tidak dapat
                        dibatalkan.</p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold"
                            data-bs-dismiss="modal">Batal</button>
                        <form method="POST" action="{{ route('notification.delete', $notification->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill fw-bold">Ya, Hapus
                                Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-all {
            transition: all 0.3s ease;
        }

        .transition-all:hover {
            transform: translateY(-2px);
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection
