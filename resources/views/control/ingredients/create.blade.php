@extends('layouts.control')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">Tambah Bahan Baku Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('control.ingredients.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Nama Bahan</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        placeholder="Contoh: Tepung Terigu" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Kategori</label>
                                    <select name="ingredient_category_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('ingredient_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Harga per Unit (Rp)</label>
                                    <input type="number" name="price_per_unit" class="form-control"
                                        value="{{ old('price_per_unit') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Stok Awal</label>
                                    <input type="number" name="stock_quantity" class="form-control"
                                        value="{{ old('stock_quantity') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Satuan (Unit)</label>
                                    <input type="text" name="unit" class="form-control" value="{{ old('unit') }}"
                                        placeholder="Contoh: kg, ltr, pcs" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Minimum Stock Level (Alert)</label>
                                <input type="number" name="minimum_stock_level" class="form-control"
                                    value="{{ old('minimum_stock_level', 5) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold">Gambar Bahan</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <small class="text-muted">Format yang di dukung JPG</small>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('control.ingredients.index') }}" class="btn btn-light border">Batal</a>
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
