@extends('layouts.store')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 400px;">
        <h3 class="fw-bold text-center mb-4">Login</h3>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
        </form>
        <p class="text-center mt-3 small">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
    </div>
</div>
@endsection