@extends('adminlte::page')

@section('title', 'Warehouse Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="font-weight-bold text-dark">Warehouse List</h1>
        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddWarehouse">
            <i class="fas fa-plus-circle mr-1"></i> Add New Warehouse
        </button>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <x-adminlte-card title="Operational Warehouses" theme="navy" icon="fas fa-warehouse" shadow>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Code</th>
                            <th>Warehouse Name</th>
                            <th>Address</th>
                            <th class="text-center">Stocks Count</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouses as $wh)
                        <tr>
                            <td><code class="text-primary fw-bold">{{ $wh->code }}</code></td>
                            <td><strong>{{ $wh->name }}</strong></td>
                            <td>{{ $wh->address ?? '-' }}</td>
                            <td class="text-center">
                                {{-- Menampilkan jumlah baris stok yang ada di gudang ini --}}
                                <span class="badge badge-pill badge-info">{{ $wh->stockLedgers->count() }} Records</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    {{-- Tombol Edit (Memicu Modal Edit) --}}
                                    <button class="btn btn-xs btn-outline-primary mx-1 shadow-sm btn-edit" 
                                            data-id="{{ $wh->id }}" 
                                            data-name="{{ $wh->name }}" 
                                            data-code="{{ $wh->code }}" 
                                            data-address="{{ $wh->address }}"
                                            data-toggle="modal" data-target="#modalEditWarehouse">
                                        <i class="fa fa-pen"></i>
                                    </button>

                                    {{-- Form Hapus --}}
                                    <form action="{{ route('admin.warehouses.destroy', $wh->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-xs btn-outline-danger mx-1 shadow-sm btn-delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No warehouses found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-adminlte-card>
    </div>
</div>

{{-- Modal Add Warehouse --}}
<form action="{{ route('admin.warehouses.store') }}" method="POST">
    @csrf
    <x-adminlte-modal id="modalAddWarehouse" title="Create New Warehouse" theme="navy" icon="fas fa-plus">
        <x-adminlte-input name="code" label="Warehouse Code" placeholder="e.g. WH-JKT-01" required />
        <x-adminlte-input name="name" label="Warehouse Name" placeholder="e.g. Jakarta Main Center" required />
        <x-adminlte-textarea name="address" label="Full Address" placeholder="Street name, City..." rows=3 />
        <x-slot name="footerSlot">
            <x-adminlte-button theme="success" label="Save Warehouse" type="submit" icon="fas fa-save"/>
        </x-slot>
    </x-adminlte-modal>
</form>

{{-- Modal Edit Warehouse --}}
<form id="editForm" action="" method="POST">
    @csrf
    @method('PUT')
    <x-adminlte-modal id="modalEditWarehouse" title="Edit Warehouse" theme="primary" icon="fas fa-edit">
        <x-adminlte-input name="code" id="edit_code" label="Warehouse Code" required />
        <x-adminlte-input name="name" id="edit_name" label="Warehouse Name" required />
        <x-adminlte-textarea name="address" id="edit_address" label="Full Address" rows=3 />
        <x-slot name="footerSlot">
            <x-adminlte-button theme="primary" label="Update Warehouse" type="submit" icon="fas fa-save"/>
        </x-slot>
    </x-adminlte-modal>
</form>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // 1. Notifikasi Toast Sukses
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // 2. Logic Edit Modal (Isi data otomatis)
        $('.btn-edit').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let code = $(this).data('code');
            let address = $(this).data('address');

            // Update action URL form
            $('#editForm').attr('action', `/admin/warehouses/${id}`);
            
            // Isi field input
            $('#edit_name').val(name);
            $('#edit_code').val(code);
            $('#edit_address').val(address);
        });

        // 3. Konfirmasi Hapus
        $('.btn-delete').click(function() {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Gudang?',
                text: "Data stok di gudang ini mungkin akan terpengaruh!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@stop