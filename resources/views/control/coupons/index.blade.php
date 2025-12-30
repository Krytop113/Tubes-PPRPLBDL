@extends('layouts.control')

@section('title', 'Daftar Coupon')
@section('page-title', 'Manajemen Coupon')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">List Coupon</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('control.ingredients.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i> Tambah Coupon
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Judul</th>
                                <th>Discount</th>
                                <th>Start</th>
                                <th>End</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $item->title }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                            {{ $item->discount_percentage }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold 'text-dark'">
                                            {{ $item->start_date }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold 'text-dark'">
                                            {{ $item->end_date }}
                                        </span>
                                    </td>
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
                                        Data Coupon tidak ditemukan.
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
