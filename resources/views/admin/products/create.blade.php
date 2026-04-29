{{-- resources/views/admin/products/create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Add New Product')

@section('content_header')
    <h1>Create Professional Product</h1>
@stop

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">

        {{-- LEFT --}}
        <div class="col-md-7">

            <x-adminlte-card title="General Information" theme="navy" icon="fas fa-info-circle">
                <div class="row">

                    <x-adminlte-input
                        name="name"
                        label="Product Name"
                        placeholder="Apple MacBook Pro M3"
                        fgroup-class="col-md-12"
                        enable-old-support
                        required
                    />

                    <x-adminlte-select
                        name="category_id"
                        label="Category"
                        fgroup-class="col-md-6"
                        required
                    >
                        <option value="">Select Category</option>

                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </x-adminlte-select>

                    <x-adminlte-input
                        name="brand"
                        label="Brand"
                        placeholder="Apple"
                        fgroup-class="col-md-6"
                        enable-old-support
                    />

                    <div class="col-md-12">
                        <label>Detailed Description</label>
                        <textarea
                            name="description"
                            class="form-control"
                            rows="5"
                        >{{ old('description') }}</textarea>
                    </div>

                </div>
            </x-adminlte-card>

        </div>

        {{-- RIGHT --}}
        <div class="col-md-5">

            <x-adminlte-card title="Main Image" theme="dark" icon="fas fa-image">

                <x-adminlte-input-file
                    name="image"
                    label="Upload Featured Photo"
                    placeholder="Choose image..."
                    igroup-size="sm"
                >
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-upload"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>

                <div class="alert alert-info py-2 mt-2">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        Max size 2MB. JPG / PNG / WEBP
                    </small>
                </div>

            </x-adminlte-card>

            <x-adminlte-card title="Status & Visibility" theme="secondary">

                <x-adminlte-select name="is_active" label="Publish Status">
                    <option value="1" {{ old('is_active',1) == 1 ? 'selected' : '' }}>
                        Active / Published
                    </option>

                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                        Draft / Inactive
                    </option>
                </x-adminlte-select>

            </x-adminlte-card>

        </div>

        {{-- VARIANT --}}
        <div class="col-md-12">

            <x-adminlte-card
                title="Product Variants"
                theme="lightblue"
                icon="fas fa-boxes"
                collapsible
            >

                <div class="table-responsive">
                    <table class="table table-bordered" id="variant-table">

                        <thead class="bg-light">
                        <tr>
                            <th>SKU</th>
                            <th>Variant</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Weight</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="variants[0][sku]" class="form-control" required>
                            </td>

                            <td>
                                <input type="text" name="variants[0][values]" class="form-control"
                                       placeholder='{"Color":"Black"}' required>
                            </td>

                            <td>
                                <input type="number" name="variants[0][price]" class="form-control" min="0" required>
                            </td>

                            <td>
                                <input type="number" name="variants[0][stock]" class="form-control" min="0" required>
                            </td>

                            <td>
                                <input type="number" name="variants[0][weight]" class="form-control" min="0">
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                </div>

                <button type="button" class="btn btn-outline-primary mt-2" id="add-variant-row">
                    <i class="fas fa-plus"></i> Add Variant
                </button>

            </x-adminlte-card>

            <div class="mb-5">
                <button type="submit" class="btn btn-success btn-lg float-right px-5">
                    <i class="fas fa-save"></i> Save Product
                </button>
            </div>

        </div>

    </div>
</form>
@stop


@section('js')
<script>
$(function () {

    let variantCount = 1;

    $('#add-variant-row').on('click', function () {

        let row = `
        <tr>
            <td>
                <input type="text" name="variants[${variantCount}][sku]" class="form-control" required>
            </td>

            <td>
                <input type="text" name="variants[${variantCount}][values]" class="form-control"
                       placeholder='{"Size":"XL"}' required>
            </td>

            <td>
                <input type="number" name="variants[${variantCount}][price]" class="form-control" min="0" required>
            </td>

            <td>
                <input type="number" name="variants[${variantCount}][stock]" class="form-control" min="0" required>
            </td>

            <td>
                <input type="number" name="variants[${variantCount}][weight]" class="form-control" min="0">
            </td>

            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;

        $('#variant-table tbody').append(row);
        variantCount++;
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

});
</script>
@stop