@extends('adminlte::page')

@section('title', 'Category & Attributes')

@section('content_header')
    <h1 class="font-weight-bold">Category Management</h1>
@stop

@section('content')
<div class="row">
    {{-- List Category --}}
    <div class="col-md-8">
        <x-adminlte-card title="Existing Categories" theme="navy" icon="fas fa-list" shadow>
            <div class="table-responsive">
                <table class="table table-hover text-nowrap align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Attributes (EAV)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td><strong>{{ $cat->name }}</strong></td>
                            <td>
                                @if($cat->attributes_definition && is_array($cat->attributes_definition))
                                    @foreach($cat->attributes_definition as $attr)
                                        <span class="badge badge-info shadow-sm">{{ $attr }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small"><em>No attributes defined</em></span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-xs btn-outline-primary mx-1 shadow-sm" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    
                                    {{-- Form Delete --}}
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="category-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-xs btn-outline-danger mx-1 shadow-sm btn-category-delete" title="Delete">
                                            <i class="fa fa-trash"></i>
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
    </div>

    {{-- Form Add Category --}}
    <div class="col-md-4">
        <x-adminlte-card title="Add New Category" theme="dark" shadow>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <x-adminlte-input name="name" label="Category Name" placeholder="e.g. Electronics" required />
                
                <div class="form-group">
                    <label>Attributes Definition</label>
                    <div id="attribute-wrapper">
                        <div class="input-group mb-2 shadow-sm">
                            <input type="text" name="attributes[]" class="form-control" placeholder="e.g. Brand">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success add-attr"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Gunakan tombol + untuk menambah spesifikasi.</small>
                </div>
                
                <x-adminlte-button class="btn-flat float-right shadow-sm" type="submit" label="Save Category" theme="success" icon="fas fa-save"/>
            </form>
        </x-adminlte-card>
    </div>
</div>
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

        // 2. Alert untuk Error
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
            });
        @endif

        // 3. Konfirmasi Hapus Kategori
        $('.btn-category-delete').click(function(e) {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Pastikan kategori ini tidak memiliki produk aktif sebelum dihapus!",
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

        // 4. Input Dinamis Atribut
        $('.add-attr').click(function() {
            let html = `
                <div class="input-group mb-2 shadow-sm">
                    <input type="text" name="attributes[]" class="form-control" placeholder="Atribut baru...">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-attr"><i class="fas fa-minus"></i></button>
                    </div>
                </div>`;
            $('#attribute-wrapper').append(html);
        });

        $(document).on('click', '.remove-attr', function() {
            $(this).closest('.input-group').remove();
        });
    });
</script>
@stop