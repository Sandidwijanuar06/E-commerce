@extends('adminlte::page')

@section('title', 'Shipment Tracking')

@section('content_header')
    <h1>Shipment Tracking</h1>
@stop

@section('content')
<x-adminlte-card title="Active Shipments" theme="navy" icon="fas fa-truck-loading">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Courier & Service</th>
                <th>Tracking Number</th>
                <th>Last Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
            <tr>
                <td>#{{ $shipment->order_number }}</td>
                <td>{{ $shipment->user->name }}</td>
                <td><span class="badge badge-outline-dark">JNE - Reguler</span></td>
                <td><code>{{ $shipment->tracking_number }}</code></td>
                <td><span class="badge badge-info">On Process</span></td>
                <td>
                    <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="btn btn-xs btn-primary shadow-sm">
                        <i class="fas fa-search-location"></i> Track Position
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $shipments->links() }}
</x-adminlte-card>
@stop