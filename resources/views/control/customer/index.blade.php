@extends('layouts.control')

@section('title', 'Daftar Pengguna')
@section('page-title', 'Daftar Pengguna')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">Daftar Pengguna</h3>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user as $index => $user)
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
                                        <span class="fw-bold 'text-dark'">
                                            {{ $user->phone_number }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($user->date_of_birth === null)
                                            <span class="fw-bold 'text-dark'">
                                                -
                                            </span>
                                        @else
                                            <span class="fw-bold 'text-dark'">
                                                {{ $user->date_of_birth }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users fa-2x" style="color: #6c757d"></i>
                                        <br>
                                        Data Customer tidak ditemukan.
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
