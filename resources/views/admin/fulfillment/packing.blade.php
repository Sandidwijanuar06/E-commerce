@extends('adminlte::page')

@section('title', 'Fulfillment | Packing List')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Packing List (Daily Tasks)</h1>
        <div>
            <span class="badge badge-info p-2"><i class="fas fa-boxes"></i> {{ $orders->count() }} Orders Pending</span>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    @forelse($orders as $order)
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-primary shadow">
            <div class="card-header">
                <h3 class="card-title"><strong>#{{ $order->order_number }}</strong></h3>
                <div class="card-tools">
                    <small class="text-muted"><i class="far fa-clock"></i> {{ $order->created_at->diffForHumans() }}</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-3 bg-light border-bottom">
                    <p class="mb-0"><strong>Customer:</strong> {{ $order->user->name }}</p>
                    <p class="mb-0 small text-muted"><i class="fas fa-map-marker-alt"></i> {{ $order->shipping_address_snapshot['city'] }}</p>
                </div>
                
                <ul class="list-group list-group-flush">
                    @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="font-weight-bold">{{ $item->quantity }}x</span> 
                            {{ $item->variant->product->name }}
                            <br>
                            <small class="text-info font-italic">SKU: {{ $item->variant->sku }}</small>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check-{{ $item->id }}">
                            <label class="custom-control-label" for="check-{{ $item->id }}"></label>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer bg-white border-top">
                <div class="btn-group w-100">
                    <button class="btn btn-outline-secondary btn-sm"><i class="fas fa-print"></i> Label</button>
                    <form action="{{ route('admin.fulfillment.mark_packed', $order->id) }}" method="POST" class="w-100 ml-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm btn-block">
                            <i class="fas fa-check"></i> Mark as Packed
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
        <h3>All caught up! No pending packing tasks.</h3>
    </div>
    @endforelse
</div>
@stop

@section('css')
<style>
    .custom-control-input:checked ~ .list-group-item {
        background-color: #f8f9fa;
        text-decoration: line-through;
        color: #adb5bd;
    }
</style>
@stop