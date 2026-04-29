@extends('adminlte::page')

@section('title', 'Marketing | Coupons')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="font-weight-bold text-dark">Coupons & Promotions</h1>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-1"></i> Create New Coupon
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <x-adminlte-card title="Active Promotions" theme="navy" icon="fas fa-ticket-alt" shadow>
            <div class="table-responsive">
                <table class="table table-hover table-striped text-nowrap align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Coupon Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th style="width: 200px;">Usage (Limit)</th>
                            <th>Validity Period</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle p-2 rounded mr-2">
                                        <i class="fas fa-tag text-primary"></i>
                                    </div>
                                    <strong class="text-primary" style="letter-spacing: 1px;">{{ $coupon->code }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-outline-secondary">{{ ucfirst($coupon->type) }}</span>
                            </td>
                            <td class="font-weight-bold text-dark">
                                {{ $coupon->type == 'percentage' ? $coupon->value.'%' : 'Rp '.number_format($coupon->value, 0, ',', '.') }}
                            </td>
                            <td>
                                @php 
                                    $percent = ($coupon->used / $coupon->limit) * 100;
                                    $progressClass = $percent > 80 ? 'bg-danger' : ($percent > 50 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <div class="progress progress-xs mb-1 shadow-sm">
                                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $percent }}%"></div>
                                </div>
                                <small class="font-weight-bold text-muted">{{ $coupon->used }} / {{ $coupon->limit }} Used</small>
                            </td>
                            <td>
                                <small class="text-dark">
                                    <i class="fas fa-calendar-alt mr-1 text-muted"></i> 
                                    {{ $coupon->start_date->format('d M') }} - {{ $coupon->end_date->format('d M Y') }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if($coupon->is_active && $coupon->end_date->isFuture())
                                    <span class="badge badge-success px-3 rounded-pill">Active</span>
                                @else
                                    <span class="badge badge-danger px-3 rounded-pill">Expired/Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-xs btn-outline-primary mx-1 shadow-sm" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    
                                    {{-- Form Delete --}}
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="delete-form-coupon">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-xs btn-outline-danger mx-1 shadow-sm btn-delete-coupon" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No coupons available at the moment.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // 1. Toast Success Notification
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

        // 2. Global Error Alert
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
            });
        @endif

        // 3. Delete Confirmation SweetAlert2
        $('.btn-delete-coupon').click(function(e) {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Kupon?',
                text: "Kupon yang dihapus tidak dapat digunakan lagi oleh pelanggan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@stop