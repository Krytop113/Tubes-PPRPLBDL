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

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kupon Belanja</h2>
            <p class="text-secondary">Pilih kupon diskon yang tersedia untuk Anda.</p>
        </div>

        <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-4 fw-bold" id="pills-available-tab" data-bs-toggle="pill"
                    data-bs-target="#available" type="button" role="tab">Tersedia</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 fw-bold" id="pills-mine-tab" data-bs-toggle="pill" data-bs-target="#mine"
                    type="button" role="tab">Milik Saya</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="available" role="tabpanel">
                <div class="row g-4">
                    @forelse($coupons as $coupon)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                                <div class="row g-0 h-100">
                                    <div
                                        class="col-4 bg-primary bg-gradient d-flex align-items-center justify-content-center text-white">
                                        <h2 class="fw-bold mb-0">{{ number_format($coupon->discount_percentage, 0) }}%
                                        </h2>
                                    </div>

                                    <div class="col-8 bg-white ticket-dashed">
                                        <div class="card-body d-flex flex-column justify-content-between h-100">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">{{ $coupon->title }}</h6>
                                                <p class="text-muted mb-2" style="font-size: 0.75rem; line-height: 1.2;">
                                                    {{ $coupon->description }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-danger small fw-bold mb-2" style="font-size: 0.7rem;">
                                                    <i class="bi bi-clock me-1"></i>Hingga:
                                                    {{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}
                                                </p>
                                                <form action="{{ route('coupons.claim', $coupon->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">Klaim</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">Belum ada kupon tersedia.</div>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="mine" role="tabpanel">
                <div class="row g-4">
                    @forelse($usercoupons as $usercoupon)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                                <div class="row g-0 h-100">
                                    <div
                                        class="col-4 bg-secondary d-flex align-items-center justify-content-center text-white">
                                        <h2 class="fw-bold mb-0">
                                            {{ number_format($usercoupon->coupon->discount_percentage, 0) }}%</h2>
                                    </div>
                                    <div class="col-8 bg-white ticket-dashed">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-1">{{ $usercoupon->coupon->title }}</h6>
                                            <p class="text-muted mb-2" style="font-size: 0.7rem;">
                                                {{ $usercoupon->coupon->description }}
                                            </p>
                                            <p class="text-danger fw-bold mb-2" style="font-size: 0.65rem;">
                                                Berakhir:
                                                {{ \Carbon\Carbon::parse($usercoupon->coupon->end_date)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">Kamu belum memiliki kupon.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    </script>
@endsection
