@extends('layouts.store')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Produk Kami</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Filter</h5>
                    <form action="{{ route('store.products') }}" method="GET">
                        
                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase">Cari Nama</label>
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Contoh: Kemeja...">
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase">Kategori</label>
                            @foreach($categories as $cat)
                            <div class="form-check small">
                                <input class="form-check-input" type="checkbox" name="category[]" value="{{ $cat->slug }}" id="cat-{{ $cat->id }}"
                                    {{ is_array(request('category')) && in_array($cat->slug, request('category')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat-{{ $cat->id }}">
                                    {{ $cat->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase">Harga (Rp)</label>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text">Min</span>
                                <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Max</span>
                                <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill">Terapkan Filter</button>
                        <a href="{{ route('store.products') }}" class="btn btn-link btn-sm w-100 text-decoration-none text-muted mt-2">Reset</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="mb-0 text-muted small">Menampilkan <strong>{{ $products->count() }}</strong> produk</p>
                <div class="d-flex align-items-center">
                    <span class="small text-muted me-2 text-nowrap">Urutkan:</span>
                    <select class="form-select form-select-sm border-0 bg-light shadow-none">
                        <option value="latest">Terbaru</option>
                        <option value="price_low">Harga Terendah</option>
                        <option value="price_high">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-6 col-md-4 mb-4">
                    <a href="{{ route('store.product.detail', $product->slug) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 product-card border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400?text=No+Image' }}" 
                                    class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                
                                @php 
                                    // Menggunakan accessor 'stock' yang kamu buat di model ProductVariant
                                    $totalStock = $product->variants->sum('stock'); 
                                @endphp

                                @if($totalStock <= 0)
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                                        style="background: rgba(255,255,255,0.7); z-index: 2;">
                                        <span class="badge bg-danger px-3 py-2 shadow-sm">Stok Habis</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body p-3 d-flex flex-column">
                                <p class="text-muted small mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                <h6 class="fw-bold text-truncate mb-2" title="{{ $product->name }}">{{ $product->name }}</h6>
                                
                                <p class="text-danger fw-bold mb-2 mt-auto">
                                    @if($product->variants->count() > 0)
                                        Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}
                                    @endif
                                </p>

                                <div class="d-flex align-items-center small text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="text-muted ms-1 small">(4.8)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5>Produk tidak ditemukan</h5>
                </div>
            @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
    }
    .form-check-input:checked {
        background-color: #2c3e50;
        border-color: #2c3e50;
    }
</style>
@endsection