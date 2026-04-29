@extends('adminlte::page')

@section('title', 'Fulfillment | Returns & Refunds')

@section('content_header')
    <h1>Returns & Refund Requests</h1>
@stop

@section('content')
<x-adminlte-card title="Incoming Return Requests" theme="navy" icon="fas fa-undo-alt" shadow>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>ID Retur</th>
                <th>Order</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $ret)
            <tr>
                <td><strong>{{ $ret->return_number }}</strong></td>
                <td>#{{ $ret->order->order_number }}</td>
                <td>{{ $ret->order->user->name }}</td>
                <td><span class="text-danger font-weight-bold">Rp {{ number_format($ret->refund_amount) }}</span></td>
                <td><small class="text-muted">{{ Str::limit($ret->reason, 40) }}</small></td>
                <td>
                    @php
                        $statusTheme = [
                            'pending' => 'warning',
                            'received' => 'info',
                            'approved' => 'success',
                            'rejected' => 'danger'
                        ];
                    @endphp
                    <span class="badge badge-{{ $statusTheme[$ret->status] ?? 'secondary' }}">
                        {{ strtoupper($ret->status) }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-xs btn-default text-primary shadow-sm" data-toggle="modal" data-target="#modal-{{ $ret->id }}">
                        <i class="fas fa-eye"></i> Review
                    </button>
                </td>
            </tr>

            {{-- Modal Review Per Retur --}}
            <x-adminlte-modal id="modal-{{ $ret->id }}" title="Review Return: {{ $ret->return_number }}" theme="navy" size='lg'>
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>Reason:</strong></h6>
                        <p class="bg-light p-2 rounded">{{ $ret->reason }}</p>
                        
                        <h6><strong>Evidence:</strong></h6>
                        <div class="d-flex flex-wrap">
                            {{-- Simulasi foto bukti --}}
                            <img src="https://via.placeholder.com/150" class="img-thumbnail mr-2 mb-2" style="width: 100px;">
                        </div>
                    </div>
                    <div class="col-md-6 border-left">
                        <form action="{{ route('admin.fulfillment.returns.update', $ret->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="form-group">
                                <label>Decision</label>
                                <select name="status" class="form-control">
                                    <option value="received" {{ $ret->status == 'received' ? 'selected' : '' }}>Item Received (Checking)</option>
                                    <option value="approved" {{ $ret->status == 'approved' ? 'selected' : '' }}>Approve & Refund</option>
                                    <option value="rejected" {{ $ret->status == 'rejected' ? 'selected' : '' }}>Reject Return</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Admin Note (Visible to Customer)</label>
                                <textarea name="admin_note" class="form-control" rows="3">{{ $ret->admin_note }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Decision</button>
                        </form>
                    </div>
                </div>
            </x-adminlte-modal>
            @endforeach
        </tbody>
    </table>
</x-adminlte-card>
@stop