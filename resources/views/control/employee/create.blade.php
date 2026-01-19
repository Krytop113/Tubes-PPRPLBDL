@extends('layouts.control')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">Tambah Karyawan Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('control.employee.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Nomor Telepon</label>
                                    <input type="text" name="phone_number" class="form-control" value="" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Tanggal lahir</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" value="" required>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('control.employee') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Data Karyawan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
