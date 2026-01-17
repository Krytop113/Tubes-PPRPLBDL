@extends('layouts.control')

@section('title', 'Daftar Bahan Baku')
@section('page-title', 'Manajemen Bahan Baku')

@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">List Bahan Baku</h3>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                    <i class="fas fa-bolt me-2"></i> Cepat Tambah Stok
                </button>
                <a href="{{ route('control.ingredients.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i> Tambah Bahan Baku
                </a>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Cari nama bahan baku..." value="{{ $search ?? '' }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ ($selectedCategory ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        <a href="{{ route('control.ingredients.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Nama Bahan</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ingredients as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $item->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                            {{ $item->ingredient_category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="fw-bold {{ $item->stock_quantity <= $item->minimum_stock_level ? 'text-danger' : 'text-dark' }}">
                                            {{ $item->stock_quantity }}
                                            @if ($item->stock_quantity <= $item->minimum_stock_level)
                                                <i class="fas fa-exclamation-triangle ms-1" title="Stok Menipis!"></i>
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $item->unit }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group gap-2">
                                            <a href="{{ route('control.ingredients.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded border-0">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('control.ingredients.destroy', $item->id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded border-0 btn-delete"
                                                    data-name="{{ $item->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50"><br>
                                        Data bahan baku tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="quickAddModalLabel fw-bold">
                        <i class="fas fa-plus-circle me-2"></i>Update Stok Massal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('control.ingredients.quickadd') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted small mb-4">Pilih bahan baku dan masukkan jumlah stok yang ingin
                            <strong>ditambahkan</strong>.
                        </p>

                        <div id="quick-add-rows">
                            <div class="row g-2 mb-3 align-items-end quick-row">
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold text-uppercase">Bahan Baku</label>
                                    <select name="updates[0][id]" class="form-select select-ingredient" required>
                                        <option value="">-- Pilih Bahan --</option>
                                        @foreach ($ingredients as $ing)
                                            <option value="{{ $ing->id }}">
                                                {{ $ing->name }} (Saat ini: {{ $ing->stock_quantity }}
                                                {{ $ing->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase">Jumlah Tambahan</label>
                                    <div class="input-group">
                                        <input type="number" name="updates[0][amount]" class="form-control"
                                            placeholder="0" min="1" step="any" required>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-outline-danger border-0 remove-row"
                                        style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-more-row">
                            <i class="fas fa-plus me-1"></i> Tambah Baris Bahan
                        </button>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                const ingredientName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Bahan baku "${ingredientName}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            let rowIndex = 1;
            const container = document.getElementById('quick-add-rows');
            const addBtn = document.getElementById('add-more-row');

            addBtn.addEventListener('click', function() {
                const firstRow = document.querySelector('.quick-row');
                const newRow = firstRow.cloneNode(true);

                const select = newRow.querySelector('select');
                const input = newRow.querySelector('input');
                const removeBtn = newRow.querySelector('.remove-row');

                select.name = `updates[${rowIndex}][id]`;
                select.value = "";

                input.name = `updates[${rowIndex}][amount]`;
                input.value = "";

                removeBtn.style.display = 'block';

                container.appendChild(newRow);
                rowIndex++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const rows = document.querySelectorAll('.quick-row');
                    if (rows.length > 1) {
                        e.target.closest('.quick-row').remove();
                    }
                }
            });
        });
    </script>
@endsection
