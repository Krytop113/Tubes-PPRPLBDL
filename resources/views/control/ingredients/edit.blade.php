@extends('layouts.control')

@section('title', 'Edit Bahan Baku')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">Edit Bahan Baku: {{ $ingredient->name }}</h5>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('control.ingredients.update', $ingredient->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Nama Bahan</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $ingredient->name) }}" placeholder="Contoh: Tepung Terigu"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Kategori</label>
                                    <select name="ingredient_category_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('ingredient_category_id', $ingredient->ingredient_category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold mb-1">Harga per Unit (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price_per_unit" class="form-control"
                                            value="{{ old('price_per_unit', $ingredient->price_per_unit) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold mb-1">Stok Saat Ini</label>
                                    <input type="number" name="stock_quantity" class="form-control"
                                        value="{{ old('stock_quantity', $ingredient->stock_quantity) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold mb-1">Satuan (Unit)</label>
                                    <input type="text" name="unit" class="form-control"
                                        value="{{ old('unit', $ingredient->unit) }}" placeholder="Contoh: kg, ltr, pcs"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold mb-1">Minimum Stock Level (Alert)</label>
                                <input type="number" name="minimum_stock_level" class="form-control"
                                    value="{{ old('minimum_stock_level', $ingredient->minimum_stock_level) }}" required>
                                <small class="text-muted">Sistem akan memberi peringatan jika stok di bawah angka
                                    ini.</small>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold mb-1">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ old('description', $ingredient->description) }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold mb-1">Gambar Bahan Baku</label>
                                <div class="row align-items-center">
                                    @if ($ingredient->image_url)
                                        <div class="col-auto">
                                            <img src="{{ asset('ingredients/' . $ingredient->image_url) }}"
                                                alt="Current Image" class="img-thumbnail"
                                                style="width: 125px; height: 125px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div class="col">
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Format
                                            akan diubah menjadi .jpg</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('control.ingredients.index') }}"
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
