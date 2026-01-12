@extends('layouts.control')

@section('title', 'Daftar Coupon')
@section('page-title', 'Manajemen Coupon')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">List Coupon</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('control.coupons.create') }}" class="btn btn-primary shadow-sm">
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
                                        <span class="fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group gap-2">
                                            <a href="{{ route('control.coupons.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded border-0">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('control.coupons.destroy', $item->id) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded border-0 btn-delete"
                                                    data-name="{{ $item->title }}">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                const couponName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Kupon "${couponName}" akan dihapus permanen!`,
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
    </script>
@endsection
