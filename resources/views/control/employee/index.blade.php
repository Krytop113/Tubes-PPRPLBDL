@extends('layouts.control')

@section('title', 'Daftar Karyawan')
@section('page-title', 'Daftar Karyawan')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">Daftar Karyawan</h3>
            <a href="{{ route('control.employee.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i> Tambah Karyawan
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Tanggal Lahir</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee as $index => $user)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">
                                            {{ $user->email }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">
                                            {{ $user->phone_number }}
                                        </span>
                                    </td>
                                    @if ($user->date_of_birth === null)
                                        <td>
                                            <span class="fw-bold 'text-dark'">
                                                -
                                            </span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                                {{ $user->date_of_birth }}
                                            </span>
                                        </td>
                                    @endif

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
                                        Data Resep tidak ditemukan.
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
