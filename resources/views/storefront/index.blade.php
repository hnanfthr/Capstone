@extends('layouts.storefront')

@section('content')
<!-- Hero Section -->
<div class="row align-items-center mb-5 fade-in-up py-4 py-lg-5">
    <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
        <h1 class="font-script display-3 text-dark mb-2">Open Pre-Order <span class="text-warning">{{ date('Y') }}</span></h1>
        <h2 class="fw-bold mb-3 display-4"><span class="brush-accent">Hassan's Koekjes</span></h2>
        <p class="lead text-muted mb-4">Sajian kue kering premium dengan resep otentik pilihan keluarga. Renyah, lezat, dan dibuat dengan sepenuh hati untuk momen spesial Anda.</p>
        <a href="#katalog" class="btn btn-theme btn-lg px-5 shadow-sm">Lihat Menu</a>
    </div>
    <div class="col-lg-6 text-center">
        <!-- Illustrasi Hero. Idealnya pakai gambar produk unggulan -->
        <div class="position-relative d-inline-block">
            <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 bg-warning rounded-circle opacity-25" style="filter: blur(40px); z-index: -1;"></div>
            @if($bestSellers->first() && $bestSellers->first()->foto)
                <img src="{{ asset('storage/' . $bestSellers->first()->foto) }}" class="img-fluid rounded-circle shadow-lg" alt="Kue Premium" style="max-width: 350px; border: 8px solid white;">
            @else
                <div class="bg-white rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 300px; height: 300px; border: 8px solid white;">
                    <i class="bi bi-shop text-warning" style="font-size: 6rem;"></i>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Best Sellers Section -->
@if($bestSellers->count() > 0)
<div class="mb-5 pt-4 fade-in-up">
    <h3 class="fw-bold text-center mb-4"><i class="bi bi-star-fill text-warning me-2"></i> Paling Banyak Dipesan (Best Seller)</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach($bestSellers as $product)
            <div class="col">
                <a href="{{ route('storefront.show', $product->id) }}" class="text-decoration-none">
                    <div class="card h-100 product-card border-0 shadow-sm position-relative">
                        <div class="position-absolute top-0 start-0 m-2 z-1">
                            <span class="badge bg-danger shadow-sm"><i class="bi bi-fire"></i> Best Seller</span>
                        </div>
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" class="card-img-top" alt="{{ $product->nama }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted" style="height: 220px;">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold mb-1 text-dark">{{ $product->nama }}</h5>
                            <p class="text-warning fw-bold mb-2 fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            <div class="star-rating small mb-2">
                                @php $rating = round($product->average_rating); @endphp
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">({{ $product->reviews->count() }})</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Top Rated Section -->
@if($topRated->count() > 0)
<div class="mb-5 py-4 bg-white rounded-4 shadow-sm px-4 fade-in-up">
    <h3 class="fw-bold text-center mb-4"><i class="bi bi-heart-fill text-danger me-2"></i> Rating Tertinggi (Top Rated)</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach($topRated as $product)
            <div class="col">
                <a href="{{ route('storefront.show', $product->id) }}" class="text-decoration-none">
                    <div class="card h-100 product-card border-0 shadow-sm bg-light">
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" class="card-img-top" alt="{{ $product->nama }}">
                        @else
                            <div class="card-img-top bg-white d-flex align-items-center justify-content-center text-muted" style="height: 220px;">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold mb-1 text-dark">{{ $product->nama }}</h5>
                            <p class="text-warning fw-bold mb-2 fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            <div class="star-rating small">
                                @php $rating = round($product->average_rating); @endphp
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- All Products Section -->
<div id="katalog" class="fade-in-up pt-4">
    <h3 class="fw-bold text-center mb-4"><i class="bi bi-grid-fill text-secondary me-2"></i> Semua Katalog Kue</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @forelse($products as $product)
            <div class="col">
                <a href="{{ route('storefront.show', $product->id) }}" class="text-decoration-none">
                    <div class="card h-100 product-card border-0 shadow-sm">
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" class="card-img-top" alt="{{ $product->nama }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted" style="height: 220px;">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title fw-bold mb-1 text-dark">{{ $product->nama }}</h5>
                            <p class="text-muted small mb-1">{{ $product->kategori }}</p>
                            <p class="text-warning fw-bold mb-3 fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-auto" onclick="event.stopPropagation();">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-warning w-100 fw-bold rounded-pill">
                                    <i class="bi bi-cart-plus"></i> Masukkan Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">Maaf, belum ada produk yang tersedia saat ini.</h4>
            </div>
        @endforelse
    </div>
</div>
@endsection
