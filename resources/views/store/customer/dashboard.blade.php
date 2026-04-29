@extends('layouts.store')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h6 class="fw-bold mb-0">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                    <div class="list-group list-group-flush small">
                        <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action active border-0 rounded-3 mb-1">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                            <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                            <i class="fas fa-heart me-2"></i> Wishlist
                        </a>
                        <a href="{{ route('customer.settings') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                            <i class="fas fa-user-cog me-2"></i> Pengaturan Akun
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action border-0 text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <h4 class="fw-bold mb-4">Ringkasan Akun</h4>
            
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="d-block mb-1">Total Pesanan</small>
                                    <h3 class="fw-bold mb-0">{{ auth()->user()->orders->count() }}</h3>
                                </div>
                                <i class="fas fa-box-open fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="d-block mb-1">Wishlist</small>
                                    <h3 class="fw-bold mb-0">{{ auth()->user()->wishlists->count() }}</h3>
                                </div>
                                <i class="fas fa-heart fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Pesanan Terakhir</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th class="ps-3">ID Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse(auth()->user()->orders()->latest()->take(5)->get() as $order)
                                <tr>
                                    <td class="ps-3 fw-bold">#{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>IDR {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('customer.order.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan terbaru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection