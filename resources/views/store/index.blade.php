@extends('layouts.store')

@section('content')
<div class="container">
    {{-- Banner Slider --}}
    <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $key => $banner)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner rounded-4 shadow-lg">
            @foreach($banners as $key => $banner)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <div class="overlay" style="position: absolute; width:100%; height:100%; background: linear-gradient(to right, rgba(0,0,0,0.7), transparent);"></div>
                <img src="{{ asset('storage/'.$banner->image_path) }}" class="d-block w-100" style="height: 450px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block text-start mb-5 pb-5">
                    <h1 class="display-4 fw-bold">{{ $banner->title }}</h1>
                    <p class="lead mb-4">{{ $banner->subtitle }}</p>
                    <a href="/products" class="btn btn-accent btn-lg shadow">Belanja Sekarang</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Popular Categories --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Kategori Populer</h4>
        </div>
        <div class="row g-3 text-center">
            @php
                $icons = ['fa-laptop', 'fa-shirt', 'fa-mobile-screen', 'fa-couch', 'fa-headphones', 'fa-camera'];
            @endphp
            @foreach($categories as $index => $cat)
            <div class="col-6 col-md-2">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="p-4 border-0 rounded-4 bg-white shadow-sm hover-shadow transition">
                        <div class="mb-3 text-accent bg-light d-inline-block p-3 rounded-circle">
                            <i class="fas {{ $icons[$index % count($icons)] }} fa-2x"></i>
                        </div>
                        <p class="mb-0 small fw-bold">{{ $cat->name }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Best Sellers --}}
    <section>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Produk Terlaris</h4>
            <a href="/products" class="text-accent fw-600 text-decoration-none">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @foreach($bestSellers as $product)
            <div class="col-6 col-lg-3">
                <div class="card h-100 product-card bg-white">
                    <div class="position-relative">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                             class="card-img-top p-3 rounded-5" 
                             alt="{{ $product->name }}"
                             style="height: 240px; object-fit: contain;">
                        <button class="btn btn-white position-absolute top-0 end-0 m-3 shadow-sm rounded-circle p-2" style="width: 35px; height:35px; display:flex; align-items:center; justify-content:center;">
                            <i class="far fa-heart text-danger"></i>
                        </button>
                    </div>
                    
                    <div class="card-body pt-0">
                        <p class="text-muted small mb-1">{{ $product->category->name ?? 'Produk' }}</p>
                        <h6 class="card-title text-truncate fw-bold mb-2">{{ $product->name }}</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning small me-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-muted small">(4.5)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold text-dark fs-5">
                                @if($product->variants->isNotEmpty())
                                    Rp{{ number_format($product->variants->first()->price, 0, ',', '.') }}
                                @else
                                    <span class="text-muted small">Harga belum tersedia</span>
                                @endif</span>
                            <a href="{{ route('store.product.detail', $product->slug) }}" class="btn btn-dark btn-sm rounded-3">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>

<style>
    .hover-shadow:hover { 
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        transform: translateY(-5px);
    }
    .transition { transition: all 0.3s; }
    .fw-600 { font-weight: 600; }
</style>
@stop