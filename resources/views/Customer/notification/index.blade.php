@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-4">Notifikasi</h3>

                @forelse($notifications as $notification)
                    <a href="{{ route('notification.show', $notification->id) }}" class="text-decoration-none">
                        <div class="card mb-3 border-0 shadow-sm p-2 {{ $notification->status === 'unread' ? 'bg-light' : '' }}"
                            style="border-radius: 15px;">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    @if ($notification->status === 'unread')
                                        <div class="bg-primary rounded-circle" style="width: 10px; height: 10px;"></div>
                                    @else
                                        <div class="bg-secondary rounded-circle opacity-25"
                                            style="width: 10px; height: 10px;"></div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $notification->title }}</h6>
                                        <small class="text-muted">{{ $notification->date->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-secondary small text-truncate" style="max-width: 400px;">
                                        {{ $notification->subject }}
                                    </p>
                                </div>
                                <div class="ms-3">
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash display-1 text-light"></i>
                        <p class="text-muted mt-3">Tidak ada notifikasi untuk Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
