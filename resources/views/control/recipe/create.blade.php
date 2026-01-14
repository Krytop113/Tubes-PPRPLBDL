@extends('layouts.control')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
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
                                    <label class="fw-bold">Porsi (Serving)</label>
                                    <input type="number" name="serving" class="form-control" value="{{ old('serving') }}"
                                        placeholder="Contoh: 4" min="1" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required
                                    placeholder="Deskripsi singkat tentang resep...">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold">Langkah-langkah</label>
                                <textarea name="steps" class="form-control" rows="5" required
                                    placeholder="Tuliskan langkah-langkah memasak secara detail...">{{ old('steps') }}</textarea>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="fw-bold text-primary"><i class="fas fa-mortar-pestle me-2"></i>Komposisi
                                        Bahan Baku</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary fw-bold"
                                        id="add-ingredient">
                                        <i class="fas fa-plus me-1"></i> Tambah Bahan
                                    </button>
                                </div>

                                <div id="ingredient-container">
                                    <div class="row g-2 mb-2 ingredient-row align-items-end">
                                        <div class="col-md-7">
                                            <label class="small text-muted">Pilih Bahan</label>
                                            <select name="ingredients[0][id]" class="form-select ing-select" required>
                                                <option value="">- Pilih Bahan Baku -</option>
                                                @foreach ($allIngredients as $ing)
                                                    <option value="{{ $ing->id }}" data-unit="{{ $ing->unit }}">
                                                        {{ $ing->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted">Jumlah Dibutuhkan</label>
                                            <div class="input-group">
                                                <input type="number" name="ingredients[0][quantity]" class="form-control"
                                                    step="0.1" placeholder="0" required>
                                                <span class="input-group-text unit-label">...</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger w-100 remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <label class="fw-bold">Gambar Resep</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <small class="text-muted">Format yang di dukung JPG</small>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('control.recipes.index') }}"
                                    class="btn btn-light border px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Simpan Resep Lengkap
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let rowIdx = 1;

        document.getElementById('add-ingredient').addEventListener('click', function() {
            const container = document.getElementById('ingredient-container');
            const html = `
            <div class="row g-2 mb-2 ingredient-row align-items-end">
                <div class="col-md-7">
                    <select name="ingredients[${rowIdx}][id]" class="form-select ing-select" required>
                        <option value="">- Pilih Bahan Baku -</option>
                        @foreach ($allIngredients as $ing)
                            <option value="{{ $ing->id }}" data-unit="{{ $ing->unit }}">{{ $ing->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="number" name="ingredients[${rowIdx}][quantity]" class="form-control" step="0.1" placeholder="0" required>
                        <span class="input-group-text unit-label">...</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            rowIdx++;
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('ing-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit') || '...';
                const row = e.target.closest('.ingredient-row');
                row.querySelector('.unit-label').textContent = unit;
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const rows = document.querySelectorAll('.ingredient-row');
                if (rows.length > 1) {
                    e.target.closest('.ingredient-row').remove();
                } else {
                    alert('Minimal harus ada satu bahan baku untuk membuat resep.');
                }
            }
        });
    </script>
@endsection
