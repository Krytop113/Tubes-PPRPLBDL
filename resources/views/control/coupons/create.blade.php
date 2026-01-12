@extends('layouts.control')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">Tambah Kupon Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('control.coupons.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="fw-bold">Judul Kupon</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                    placeholder="Contoh: MAKANENAK" required>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required
                                    placeholder="Deskripsi kupon...">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Persentase Diskon (%)</label>
                                    <input type="number" name="discount_percentage" class="form-control"
                                        value="{{ old('discount_percentage') }}" placeholder="Contoh: 10" min="1"
                                        max="100" step="0.01" required>
                                    <small class="text-muted">Masukkan nilai antara 1 - 100</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Tanggal Mulai</label>
                                    <input type="datetime-local" name="start_date" class="form-control"
                                        value="{{ old('start_date') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Tanggal Berakhir</label>
                                    <input type="datetime-local" name="end_date" class="form-control"
                                        value="{{ old('end_date') }}" required>
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('control.coupons.index') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

