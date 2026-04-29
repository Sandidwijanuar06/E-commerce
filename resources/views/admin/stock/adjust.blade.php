@extends('adminlte::page')

@section('title', 'Manual Stock Adjustment')

@section('content_header')
    <h1>Stock Adjustment</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <x-adminlte-card title="Sync Physical & Digital Stock" theme="warning" icon="fas fa-sync" shadow>
            <form action="{{ route('admin.stock.adjust') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- Select Product --}}
                    <div class="col-md-12">
                        <x-adminlte-select2 name="product_variant_id" label="Product Variant" data-placeholder="Search SKU or Product Name..." igroup-size="md">
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-warning"><i class="fas fa-box"></i></div>
                            </x-slot>
                            <option></option>
                            @foreach($variants as $v)
                                <option value="{{ $v->id }}">{{ $v->product->name }} - {{ $v->sku }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>

                    {{-- Warehouse --}}
                    <div class="col-md-6">
                        <x-adminlte-select name="warehouse_id" label="Target Warehouse">
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->code }})</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>

                    {{-- Adjustment Type --}}
                    <div class="col-md-6">
                        <x-adminlte-select name="adjustment_type" label="Action Type">
                            <option value="addition">Addition (+) - e.g. Found Stock</option>
                            <option value="subtraction">Subtraction (-) - e.g. Damaged / Lost</option>
                        </x-adminlte-select>
                    </div>

                    {{-- Quantity --}}
                    <div class="col-md-6">
                        <x-adminlte-input name="quantity" type="number" label="Quantity" min="1" placeholder="10">
                            <x-slot name="appendSlot"><div class="input-group-text">Units</div></x-slot>
                        </x-adminlte-input>
                    </div>

                    {{-- Reason --}}
                    <div class="col-md-6">
                        <x-adminlte-input name="reason" label="Adjustment Reason" placeholder="e.g. Stock Opname April 2026"/>
                    </div>
                </div>

                <div class="card-footer bg-transparent p-0 mt-3">
                    <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                        <i class="fas fa-check-circle"></i> EXECUTE ADJUSTMENT
                    </button>
                    <a href="{{ route('admin.stock.index') }}" class="btn btn-link btn-block text-muted">Back to Ledger Report</a>
                </div>
            </form>
        </x-adminlte-card>
    </div>
</div>
@stop