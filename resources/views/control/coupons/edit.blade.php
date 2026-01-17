@extends('layouts.control')

@section('title', 'Edit Kupon')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">Edit Kupon: {{ $coupon->title }}</h5>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('control.coupons.update', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="fw-bold mb-1">Judul Kupon</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $coupon->title) }}" placeholder="Contoh: MAKANENAK"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold mb-1">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required
                                    placeholder="Deskripsi kupon...">{{ old('description', $coupon->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold mb-1">Persentase Diskon (%)</label>
                                    <input type="number" name="discount_percentage" class="form-control"
                                        value="{{ old('discount_percentage', $coupon->discount_percentage) }}"
                                        placeholder="Contoh: 10" min="1" max="100" step="0.01" required>
                                    <small class="text-muted">Masukkan nilai antara 1 - 100</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold mb-1">Tanggal Mulai</label>
                                    <input type="datetime-local" name="start_date" class="form-control"
                                        value="{{ old('start_date', \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i')) }}"
                                        required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold mb-1">Tanggal Berakhir</label>
                                    <input type="datetime-local" name="end_date" class="form-control"
                                        value="{{ old('end_date', \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i')) }}"
                                        required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('control.coupons.index') }}"
                                    class="btn btn-light border px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 text-white">
                                    <i class="fas fa-edit me-1"></i> Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

