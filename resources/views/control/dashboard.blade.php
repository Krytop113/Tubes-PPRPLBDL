@extends('layouts.control')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4">Dashboard</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="background: #6366f1;">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Users</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalUsers) }}</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="background: #10b981;">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Karyawan</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalEmployees) }}</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="background: #ec4899;">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Penjualan</h6>
                            <h2 class="mb-0 fw-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="background: #f97316;">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Stok Hampir Habis</h6>
                            <h2 class="mb-0 fw-bold">{{ $lowStockItems->count() }}</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($lowStockItems->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>Bahan dengan Stok Rendah
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Nama Bahan</th>
                                <th class="px-4 py-3">Stok Saat Ini</th>
                                <th class="px-4 py-3">Minimum Stok</th>
                                <th class="px-4 py-3">Unit</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowStockItems as $item)
                                <tr>
                                    <td class="px-4 py-3 fw-medium">{{ $item->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-danger">{{ number_format($item->stock_quantity) }}</span>
                                    </td>
                                    <td class="px-4 py-3">{{ number_format($item->minimum_stock_level) }}</td>
                                    <td class="px-4 py-3">{{ $item->unit }}</td>
                                    <td class="px-4 py-3">
                                        @if ($item->stock_quantity == 0)
                                            <span class="badge bg-dark">Habis</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Hampir Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>Semua stok bahan dalam kondisi baik.</div>
        </div>
    @endif
@endsection
