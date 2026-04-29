@extends('adminlte::page')

@section('title', 'Order Management')

@section('content_header')
    <h1>Order & Shipment Management</h1>
@stop

@section('content')
<x-adminlte-card title="Recent Transactions" theme="navy" icon="fas fa-shopping-cart">
    <div class="table-responsive">
        <table class="table table-hover table-striped" id="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><strong>#{{ $order->order_number }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td>Rp {{ number_format($order->total) }}</td>
                    <td>
                        @php
                            $themes = [
                                'pending' => 'warning',
                                'paid' => 'primary',
                                'processing' => 'info',
                                'shipped' => 'purple',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                        @endphp
                        <span class="badge badge-{{ $themes[$order->status] ?? 'secondary' }} px-2 py-1">
                            {{ strtoupper($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <button class="btn btn-xs btn-info view-detail" data-id="{{ $order->id }}" title="View Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="#" class="btn btn-xs btn-default text-navy" title="Print Invoice">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlte-card>

{{-- Modal Detail Pesanan --}}
<x-adminlte-modal id="modalDetail" title="Order Detail" theme="navy" icon="fas fa-file-invoice" size='lg' v-centered static-backdrop>
    <div id="detail-content">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="secondary" label="Close" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>
@stop

@section('js')
<script>
    $(document).on('click', '.view-detail', function() {
        let id = $(this).data('id');
        $('#modalDetail').modal('show');
        $('#detail-content').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');

        $.get(`/admin/orders/${id}`, function(data) {
            let itemsHtml = '';
            data.items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td>${item.variant.product.name} (${JSON.stringify(item.metadata)})</td>
                        <td>${item.quantity}</td>
                        <td class="text-right">Rp ${new Intl.NumberFormat().format(item.price_at_purchase)}</td>
                    </tr>`;
            });

            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>Customer Info:</strong></h6>
                        <p class="mb-0">${data.user.name}</p>
                        <p class="text-muted">${data.user.email}</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <h6><strong>Shipping Address:</strong></h6>
                        <p class="small">${data.shipping_address_snapshot.address}, ${data.shipping_address_snapshot.city}</p>
                    </div>
                </div>
                <hr>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Product</th><th>Qty</th><th class="text-right">Price</th></tr>
                    </thead>
                    <tbody>${itemsHtml}</tbody>
                </table>
                <hr>
                <form action="/admin/orders/${data.id}/status" method="POST">
                    @csrf @method('PATCH')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Update Status</label>
                                <select name="status" class="form-control form-control-sm">
                                    <option value="processing" ${data.status == 'processing' ? 'selected' : ''}>Processing</option>
                                    <option value="shipped" ${data.status == 'shipped' ? 'selected' : ''}>Shipped (Input Resi)</option>
                                    <option value="completed" ${data.status == 'completed' ? 'selected' : ''}>Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tracking Number (Resi)</label>
                                <input type="text" name="tracking_number" class="form-control form-control-sm" value="${data.tracking_number ?? ''}" placeholder="JNE-12345...">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm btn-block">Update Shipment & Status</button>
                </form>
            `;
            $('#detail-content').html(html);
        });
    });
</script>
@stop