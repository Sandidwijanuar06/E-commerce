@extends('adminlte::page')

@section('title', 'Dashboard | Enterprise E-Commerce')

@section('content_header')
    <h1>Main Dashboard</h1>
@stop

@section('css')
<style>
    /* Mengubah warna background sidebar menjadi Navy Gelap */
    .main-sidebar { background-color: #0f172a !important; }
    
    /* Branding CentralStore di Sidebar */
    .brand-link { 
        border-bottom: 1px solid #1e293b !important; 
        text-align: center; /* Opsional: agar logo di tengah */
    }

    /* Warna Hitam untuk kata pertama */
    /* Karena sidebar AdminLTE gelap, kita gunakan putih atau abu-abu terang agar terbaca, 
       tapi jika kamu ingin tetap Hitam, gunakan #000000 */
    .brand-text-front { 
        color: #ffffff !important; /* Saya sarankan putih/terang agar terlihat di sidebar navy */
        font-weight: 800;
    }

    /* Warna Merah untuk kata kedua */
    .brand-text-back { 
        color: #ff4757 !important; 
        font-weight: 800;
        margin-left: 2px;
    }

    /* Tambahan agar teks menu tidak tabrakan */
    .sidebar-dark-danger .nav-sidebar > .nav-item > .nav-link {
        color: #cdd5e0;
    }
</style>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Row 1: Info Boxes --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <x-adminlte-info-box title="Revenue" text="Rp {{ number_format($total_revenue) }}" icon="fas fa-lg fa-wallet text-white" theme="success"/>
            </div>
            <div class="col-lg-3 col-6">
                <x-adminlte-info-box title="Orders" text="{{ $total_orders }}" icon="fas fa-lg fa-shopping-cart text-white" theme="info"/>
            </div>
            <div class="col-lg-3 col-6">
                <x-adminlte-info-box title="Customers" text="{{ $total_customers }}" icon="fas fa-lg fa-users text-white" theme="primary"/>
            </div>
            <div class="col-lg-3 col-6">
                <x-adminlte-info-box title="Low Stock" text="{{ $low_stock_count }} Variants" icon="fas fa-lg fa-exclamation-triangle text-white" theme="warning"/>
            </div>
        </div>

        <div class="row">
            {{-- Column 1: Sales Chart --}}
            <div class="col-md-8">
                <x-adminlte-card title="Sales Trend (Last 7 Days)" theme="navy" icon="fas fa-chart-line">
                    <canvas id="salesChart" style="height: 300px;"></canvas>
                </x-adminlte-card>
            </div>

            {{-- Column 2: Recent Transactions --}}
            <div class="col-md-4">
                <x-adminlte-card title="Recent Orders" theme="dark" icon="fas fa-history">
                    <ul class="list-group list-group-flush">
                        @foreach($recent_orders as $order)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>#{{ $order->order_number }}</strong><br>
                                    <small class="text-muted">{{ $order->user->name }}</small>
                                </div>
                                <span class="badge badge-{{ $order->status == 'paid' ? 'success' : 'warning' }} px-2 py-1">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <x-slot name="footerSlot">
                        <a href="#" class="btn btn-sm btn-outline-primary btn-block">View All Orders</a>
                    </x-slot>
                </x-adminlte-card>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($sales_chart->pluck('date')) !!},
            datasets: [{
                label: 'Revenue (Rp)',
                data: {!! json_encode($sales_chart->pluck('total')) !!},
                borderColor: '#001f3f',
                backgroundColor: 'rgba(0, 31, 63, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>
@stop