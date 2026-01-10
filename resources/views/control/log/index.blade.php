@extends('layouts.control')

@section('title', 'Log Kegiatan')
@section('page-title', 'Log Kegiatan')

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
                                <th>user_id</th>
                                <th>Pengguna</th>
                                <th>Aksi</th>
                                <th>Deskrips</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($log as $index => $action)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $action->user_id }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                            {{ $action->pengguna }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold 'text-dark'">
                                            {{ $action->title }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold 'text-dark'">
                                            {{ $action->action }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold 'text-dark'">
                                            {{ $action->created_at }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50"><br>
                                        Data Log tidak ditemukan.
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
