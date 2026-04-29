@extends('layouts.store')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.products') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Gambar Produk --}}
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x600' }}" 
                     class="img-fluid" alt="{{ $product->name }}">
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="col-md-6">
            <div class="ps-md-4">
                <span class="badge bg-secondary mb-2">{{ $product->category->name ?? 'Uncategorized' }}</span>
                <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
                
                <h2 class="text-danger fw-bold mb-4">
                    Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                </h2>

                <p class="text-muted mb-4">{{ $product->description ?? 'Tidak ada deskripsi produk.' }}</p>

                {{-- Pilih Varian (Contoh) --}}
                <div class="mb-4">
                    <label class="fw-bold mb-2 small text-uppercase">Pilih Varian</label>
                    <select class="form-select shadow-none">
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}" {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                {{ is_array($variant->variant_values) ? implode(' / ', $variant->variant_values) : $variant->sku }} 
                                (Stok: {{ $variant->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-grid gap-2 d-md-flex">
                    <button class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm" {{ $product->variants->sum('stock') <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart me-2"></i> Beli Sekarang
                    </button>
                    <button class="btn btn-outline-secondary btn-lg rounded-pill">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection