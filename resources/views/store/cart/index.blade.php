@extends('layouts.store')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Keranjang Belanja</h2>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            {{-- Daftar Produk di Keranjang --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 p-3">Produk</th>
                                    <th class="border-0 p-3">Harga</th>
                                    <th class="border-0 p-3">Jumlah</th>
                                    <th class="border-0 p-3">Subtotal</th>
                                    <th class="border-0 p-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr>
                                        <td class="p-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/100' }}" 
                                                     alt="{{ $details['name'] }}" 
                                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div class="ms-3">
                                                    <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                    <small class="text-muted">{{ $details['variant_name'] ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 text-danger fw-bold">
                                            Rp {{ number_format($details['price'], 0, ',', '.') }}
                                        </td>
                                        <td class="p-3" style="width: 150px;">
                                            <div class="input-group input-group-sm border rounded">
                                                <button class="btn btn-link px-2 text-dark shadow-none border-0"><i class="fas fa-minus"></i></button>
                                                <input type="number" value="{{ $details['quantity'] }}" class="form-control border-0 text-center shadow-none bg-white" readonly>
                                                <button class="btn btn-link px-2 text-dark shadow-none border-0"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </td>
                                        <td class="p-3 fw-bold text-dark">
                                            Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                        </td>
                                        <td class="p-3 text-end">
                                            <form action="{{ route('store.cart.remove', $id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger border-0 rounded-circle"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Belanja --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Harga ({{ count(session('cart')) }} barang)</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Estimasi Pajak</span>
                            <span class="text-success small">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold h5">Total Tagihan</span>
                            <span class="fw-bold h5 text-danger">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Lanjut ke Pembayaran</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-light mb-3"></i>
            <h4>Keranjangmu masih kosong</h4>
            <p class="text-muted">Yuk, cari produk menarik untuk diisi!</p>
            <a href="{{ route('store.products') }}" class="btn btn-primary rounded-pill px-4">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection