@extends('layouts.control')

@section('title', 'Log Kegiatan')
@section('page-title', 'Log Kegiatan')

@section('content')
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Dari Waktu</label>
                        <input type="datetime-local" name="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Sampai Waktu</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Cari Data</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama pelanggan atau ID..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('control.log') }}" class="btn btn-light border" title="Reset">
                            <i class="fas fa-sync-alt"></i>
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
                                <th>user_id</th>
                                <th>Pengguna</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
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
                                            {{ $action->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold 'text-dark'">
                                            {{ $action->description ?? '-' }}
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
