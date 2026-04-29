@extends('adminlte::page')

@section('title', 'Inventory Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="font-weight-bold">Inventory & Warehouse</h1>
        <button class="btn btn-warning shadow-sm" data-toggle="modal" data-target="#modalAdjustment">
            <i class="fas fa-sync-alt mr-1"></i> Stock Adjustment
        </button>
    </div>
@stop

@section('content')
<div class="row">
    {{-- Filter Card --}}
    <div class="col-12">
        <x-adminlte-card title="Filter Report" theme="info" icon="fas fa-filter" collapsible="collapsed" shadow>
            <form action="{{ route('admin.stock.index') }}" method="GET" class="row">
                <div class="col-md-4">
                    <x-adminlte-select name="warehouse_id" label="Select Warehouse">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <x-adminlte-button type="submit" label="Filter" theme="primary" class="mb-3 w-100"/>
                </div>
            </form>
        </x-adminlte-card>
    </div>

    {{-- Stock Ledger Table --}}
    <div class="col-12">
        <x-adminlte-card title="Stock Ledger History" theme="navy" icon="fas fa-history" shadow>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>Date</th>
                            <th>Product / Variant</th>
                            <th>Warehouse</th>
                            <th>Type</th>
                            <th class="text-right">Qty Mutation</th>
                            <th>Reason / Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ledgers as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $log->variant->product->name }}</strong><br>
                                <small class="text-muted">SKU: {{ $log->variant->sku }}</small>
                            </td>
                            <td><span class="badge badge-secondary shadow-sm">{{ $log->warehouse->name }}</span></td>
                            <td>
                                @php
                                    $typeBadge = [
                                        'adjustment' => 'warning',
                                        'sale' => 'danger',
                                        'purchase' => 'success',
                                        'return' => 'info',
                                        'initial_stock' => 'primary'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $typeBadge[$log->type] ?? 'dark' }} shadow-sm">
                                    {{ strtoupper(str_replace('_', ' ', $log->type)) }}
                                </span>
                            </td>
                            <td class="text-right {{ $log->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                <strong class="fs-5">{{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}</strong>
                            </td>
                            <td>
                                <span class="text-dark">{{ $log->reason ?? 'Ref: #'.$log->reference_id }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $ledgers->appends(request()->query())->links() }}
            </div>
        </x-adminlte-card>
    </div>
</div>

{{-- Modal Stock Adjustment --}}
{{-- PENTING: Form membungkus modal agar tombol submit di footerSlot berfungsi --}}
<form action="{{ route('admin.stock.adjust') }}" method="POST">
    @csrf
    <x-adminlte-modal id="modalAdjustment" title="Stock Adjustment (Manual Sync)" theme="warning" icon="fas fa-tools" size='lg'>
        <div class="row">
            <div class="col-md-6">
                <x-adminlte-select2 name="product_variant_id" label="Product Variant" data-placeholder="Select product..." required>
                    <option></option>
                    @foreach($variants as $v)
                        <option value="{{ $v->id }}">{{ $v->product->name }} - {{ $v->sku }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="col-md-6">
                <x-adminlte-select name="warehouse_id" label="Target Warehouse" required>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <div class="col-md-4">
                <x-adminlte-select name="adjustment_type" label="Action">
                    <option value="addition">Addition (+)</option>
                    <option value="subtraction">Subtraction (-)</option>
                </x-adminlte-select>
            </div>
            <div class="col-md-4">
                <x-adminlte-input name="quantity" type="number" label="Quantity" min="1" placeholder="0" required/>
            </div>
            <div class="col-md-4">
                <x-adminlte-input name="reason" label="Reason/Note" placeholder="Stock Opname 2026" required/>
            </div>
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button theme="success" label="Confirm Adjustment" type="submit" icon="fas fa-check-circle"/>
            <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>
</form>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // 1. Toast Notification untuk Sukses
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // 2. Alert untuk Error/Gagal
        @if(session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') ?? 'Pastikan semua input sudah benar.' }}",
                confirmButtonColor: '#d33',
            });
        @endif
    });
</script>
@stop