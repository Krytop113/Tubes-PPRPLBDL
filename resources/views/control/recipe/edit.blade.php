@extends('layouts.control')

@section('title', 'Edit Resep')

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

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">Edit Resep: {{ $recipe->name }}</h5>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('control.recipes.update', $recipe->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Nama Resep</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $recipe->name) }}" placeholder="Contoh: Nasi Goreng Spesial"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Kategori Resep</label>
                                    <select name="recipe_category_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('recipe_category_id', $recipe->recipe_category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Waktu Memasak (menit)</label>
                                    <input type="number" name="cook_time" class="form-control"
                                        value="{{ old('cook_time', $recipe->cook_time) }}" placeholder="Contoh: 30"
                                        min="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Porsi</label>
                                    <input type="number" name="serving" class="form-control"
                                        value="{{ old('serving', $recipe->serving) }}" placeholder="Contoh: 4"
                                        min="1" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold mb-1">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="4" required
                                    placeholder="Deskripsi singkat tentang resep...">{{ old('description', $recipe->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold mb-1">Langkah-langkah</label>
                                <textarea name="steps" class="form-control" rows="6" required
                                    placeholder="Tuliskan langkah-langkah memasak secara detail...">{{ old('steps', $recipe->steps) }}</textarea>
                                <small class="text-muted">Tuliskan langkah-langkah dengan jelas dan terperinci</small>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold mb-1">Gambar Resep</label>
                                <div class="row align-items-center">
                                    @if ($recipe->image_url)
                                        <div class="col-auto">
                                            <img src="{{ asset('recipes/' . $recipe->image_url) }}" alt="Current Image"
                                                class="img-thumbnail"
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
                                <a href="{{ route('control.recipes.index') }}" class="btn btn-light border px-4">Batal</a>
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
