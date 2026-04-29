@extends('layouts.store')

@section('content')
<div class="container">
    <div class="row">
        {{-- Alamat & Pengiriman --}}
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold"><i class="fas fa-map-marker-alt text-danger me-2"></i> Alamat Pengiriman</h5>
                    <hr>
                    <p class="mb-1 fw-bold">Sandi Dwi Januar (Rumah)</p>
                    <p class="text-muted small">Jl. Raya No. 123, Jakarta Selatan, DKI Jakarta, 12345</p>
                    <button class="btn btn-sm btn-outline-primary">Ganti Alamat</button>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold"><i class="fas fa-truck text-primary me-2"></i> Pilih Pengiriman</h5>
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="border p-3 rounded cursor-pointer border-primary bg-light">
                                <p class="mb-0 fw-bold small">JNE Reguler</p>
                                <p class="mb-0 text-muted small">Rp 12.000 (2-3 hari)</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border p-3 rounded">
                                <p class="mb-0 fw-bold small">Sicepat Best</p>
                                <p class="mb-0 text-muted small">Rp 18.000 (Esok Sampai)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Bayar --}}
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Harga (3 Barang)</span>
                        <span>Rp 1.500.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Ongkos Kirim</span>
                        <span>Rp 12.000</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total Tagihan</span>
                        <span class="fw-bold text-danger fs-5">Rp 1.512.000</span>
                    </div>
                    <button class="btn btn-accent w-100 py-3 fw-bold">PILIH PEMBAYARAN</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop