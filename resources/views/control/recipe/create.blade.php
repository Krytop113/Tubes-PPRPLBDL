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
                        <h5 class="mb-0 fw-bold text-primary">Tambah Resep Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('control.recipes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Nama Resep</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        placeholder="Contoh: Nasi Goreng Spesial" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Kategori Resep</label>
                                    <select name="recipe_category_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('recipe_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Waktu Memasak (menit)</label>
                                    <input type="number" name="cook_time" class="form-control"
                                        value="{{ old('cook_time') }}" placeholder="Contoh: 30" min="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Porsi</label>
                                    <input type="number" name="serving" class="form-control" value="{{ old('serving') }}"
                                        placeholder="Contoh: 4" min="1" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="4" required
                                    placeholder="Deskripsi singkat tentang resep...">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Langkah-langkah</label>
                                <textarea name="steps" class="form-control" rows="6" required
                                    placeholder="Tuliskan langkah-langkah memasak secara detail...">{{ old('steps') }}</textarea>
                                <small class="text-muted">Tuliskan langkah-langkah dengan jelas dan terperinci</small>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold">Gambar Resep</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <small class="text-muted">Format akan otomatis diubah menjadi .jpg</small>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('control.recipes.index') }}" class="btn btn-light border">Batal</a>
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
