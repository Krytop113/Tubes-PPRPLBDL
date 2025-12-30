@extends('layouts.control')

@section('title', 'Daftar Bahan Baku')
@section('page-title', 'Manajemen Bahan Baku')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">List Bahan Baku</h3>
            <div class="d-flex gap-2">
                <a href="" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i> Cepat Tambah Stok Bahan Baku
                </a>
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
                                placeholder="Cari nama bahan baku..." value="{{ $search }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        <a href="" class="btn btn-outline-secondary">
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
                                        <span class="fw-bold {{ $item->stock_quantity < $item->minimum_stock_level ? 'text-danger' : 'text-dark' }}">
                                            {{ $item->stock_quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $item->unit }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group gap-2">
                                            <a href="" class="btn btn-sm btn-outline-warning rounded border-0">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="" method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded border-0">
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
@endsection
