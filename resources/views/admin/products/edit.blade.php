@extends('adminlte::page')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Produk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
        <li class="breadcrumb-item active">Edit {{ $product->name }}</li>
    </ol>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Perubahan Produk</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- PENTING: Untuk proses Update di Laravel --}}

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Produk</label>
                            <div class="mb-2">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail" style="width: 150px;">
                                    <p class="small text-muted">Foto saat ini</p>
                                @endif
                            </div>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $product->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>
                
                {{-- Bagian Edit Varian (Opsional, untuk edit harga cepat) --}}
                <h5 class="fw-bold mb-3">Informasi Varian & Harga</h5>
                <div class="table-responsive">
                    <table class="table table-bordered bg-light">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Varian</th>
                                <th>Harga (Rp)</th>
                                <th>Stok Terkini</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $variant)
                            <tr>
                                <td><code>{{ $variant->sku }}</code></td>
                                <td>{{ is_array($variant->variant_values) ? implode(', ', $variant->variant_values) : $variant->variant_values }}</td>
                                <td>
                                    {{-- Menggunakan array input agar bisa di-update sekaligus di controller --}}
                                    <input type="number" name="variants[{{ $variant->id }}][price]" 
                                           class="form-control" value="{{ $variant->price }}">
                                </td>
                                <td><strong>{{ $variant->stockLedgers->sum('quantity') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-5">Simpan Perubahan</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection