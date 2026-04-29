@extends('adminlte::page')

@section('title', 'Product List')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="font-weight-bold text-dark">Product Management</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-1"></i> Add New Product
        </a>
    </div>
@stop

@section('content')
<x-adminlte-card title="All Products" theme="navy" icon="fas fa-boxes" collapsible shadow>
    <div class="table-responsive">
        <table class="table table-hover table-striped text-nowrap align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Product Details</th>
                    <th>Category</th>
                    <th class="text-center">Variants</th>
                    <th>Price Range</th>
                    <th class="text-center">Total Stock</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="rounded mr-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                    <i class="fas fa-image text-white-50"></i>
                                </div>
                            @endif
                            <div>
                                <span class="d-block font-weight-bold text-dark">{{ $product->name }}</span>
                                <small class="text-muted"><i class="fas fa-link mr-1"></i>{{ $product->slug }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-info px-2 py-1">{{ $product->category->name }}</span></td>
                    <td class="text-center"><span class="badge badge-pill badge-light border">{{ $product->variants->count() }}</span></td>
                    <td class="font-weight-bold text-dark">
                        Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }} 
                        <span class="text-muted font-weight-normal small">s/d</span><br>
                        Rp {{ number_format($product->variants->max('price'), 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @php $stock = $product->variants->sum('total_stock'); @endphp
                        @if($stock <= 10)
                            <span class="badge badge-danger p-2 shadow-sm"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $stock }}</span>
                        @else
                            <span class="badge badge-success p-2 shadow-sm">{{ $stock }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($product->is_active)
                            <span class="badge badge-success px-3 rounded-pill">Published</span>
                        @else
                            <span class="badge badge-secondary px-3 rounded-pill">Draft</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary mx-1 rounded shadow-sm" title="Edit">
                                <i class="fa fa-pen"></i>
                            </a>
                            
                            {{-- Form Delete dengan ID unik --}}
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger mx-1 rounded shadow-sm btn-delete-confirm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlte-card>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // 1. Notifikasi Toast Sukses
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

        // 2. Notifikasi Error
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6',
            });
        @endif

        // 3. Konfirmasi Hapus SweetAlert2
        $('.btn-delete-confirm').click(function(e) {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Semua data varian dan riwayat stok akan ikut terhapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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