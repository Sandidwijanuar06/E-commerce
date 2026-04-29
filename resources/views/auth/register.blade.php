@extends('layouts.store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-sm-5">
                    <h3 class="fw-bold text-center mb-2">Daftar Akun</h3>
                    <p class="text-muted text-center mb-4">Lengkapi data diri untuk mulai berbelanja</p>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="Contoh: Sandi Dwi" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" placeholder="name@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" 
                                   placeholder="Ulangi password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow-sm">
                            Daftar Sekarang
                        </button>
                    </form>

                    <hr class="my-4 text-muted">

                    <div class="text-center">
                        <p class="small text-muted mb-0">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    body {
        background-color: #f8f9fa;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
    }
</style>
@endsection