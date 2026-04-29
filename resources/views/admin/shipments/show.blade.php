@extends('adminlte::page')

@section('title', 'Track #' . $order->order_number)

@section('content_header')
    <h1>Tracking Detail: #{{ $order->order_number }}</h1>
@stop

@section('content')
<div class="row">
    {{-- Info Card --}}
    <div class="col-md-4">
        <x-adminlte-card title="Shipment Info" theme="navy">
            <strong>Customer:</strong>
            <p class="text-muted">{{ $order->user->name }}</p>
            <hr>
            <strong>Destination:</strong>
            <p class="text-muted small">
                {{ $order->shipping_address_snapshot['address'] }}, {{ $order->shipping_address_snapshot['city'] }}
            </p>
            <hr>
            <strong>Tracking Number:</strong>
            <h5 class="text-primary"><code>{{ $order->tracking_number }}</code></h5>
            <button class="btn btn-sm btn-outline-secondary btn-block"><i class="fas fa-copy"></i> Copy Resi</button>
        </x-adminlte-card>
    </div>

    {{-- Timeline --}}
    <div class="col-md-8">
        <div class="timeline">
            @foreach($tracking_logs as $log)
                <div class="time-label">
                    <span class="bg-gray-dark">{{ \Carbon\Carbon::parse($log['date'])->format('d M. Y') }}</span>
                </div>
                <div>
                    <i class="fas {{ $loop->first ? 'fa-truck-moving bg-primary' : 'fa-check bg-success' }}"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($log['date'])->format('H:i') }}</span>
                        <h3 class="timeline-header font-weight-bold text-primary">{{ $log['status'] }}</h3>
                        <div class="timeline-body">
                            {{ $log['desc'] }}
                        </div>
                    </div>
                </div>
            @endforeach
            <div>
                <i class="fas fa-dot-circle bg-gray"></i>
            </div>
        </div>
    </div>
</div>
@stop