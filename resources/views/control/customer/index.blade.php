@extends('layouts.control')

@section('title', 'Daftar Customer')
@section('page-title', 'Daftar Customer')

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
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50"><br>
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
