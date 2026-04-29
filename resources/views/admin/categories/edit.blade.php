@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1>Edit Kategori: {{ $category->name }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <x-adminlte-card title="Form Edit Kategori" theme="primary" icon="fas fa-edit">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-adminlte-input name="name" label="Nama Kategori" 
                    value="{{ old('name', $category->name) }}" required />

                <div class="form-group">
                    <label>Definisi Atribut (Spesifikasi)</label>
                    <div id="attribute-wrapper">
                        @if($category->attributes_definition)
                            @foreach($category->attributes_definition as $attr)
                            <div class="input-group mb-2">
                                <input type="text" name="attributes[]" class="form-control" value="{{ $attr }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-attr"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        
                        {{-- Input kosong untuk tambah baru --}}
                        <div class="input-group mb-2">
                            <input type="text" name="attributes[]" class="form-control" placeholder="Tambah atribut baru...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success add-attr"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Contoh: Warna, Ukuran, Garansi, dll.</small>
                </div>

                <div class="mt-4">
                    <x-adminlte-button type="submit" label="Update Kategori" theme="primary" icon="fas fa-save"/>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Batal</a>
                </div>
            </form>
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Tambah field atribut
        $('.add-attr').click(function() {
            let html = `
                <div class="input-group mb-2">
                    <input type="text" name="attributes[]" class="form-control" placeholder="Atribut baru...">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-attr"><i class="fas fa-minus"></i></button>
                    </div>
                </div>`;
            $('#attribute-wrapper').append(html);
        });

        // Hapus field atribut
        $(document).on('click', '.remove-attr', function() {
            $(this).closest('.input-group').remove();
        });
    });
</script>
@stop