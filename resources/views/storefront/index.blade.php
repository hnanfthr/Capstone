@extends('layouts.storefront')

@section('content')
<!-- Hero Carousel Section -->
@if($banners->count() > 0)
<div id="heroCarousel" class="carousel slide carousel-fade mb-5 fade-in-up mt-lg-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($banners as $index => $banner)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    
    <div class="carousel-inner rounded-4 shadow-lg overflow-hidden">
        @foreach($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" style="height: 400px; background-color: #000;">
                <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100 h-100 object-fit-cover opacity-75" alt="{{ $banner->title }}">
                
                @if($banner->title || $banner->description)
                <div class="carousel-caption d-flex flex-column h-100 justify-content-center align-items-center text-center pb-5" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%); left: 0; right: 0; bottom: 0;">
                    <div class="container px-4 px-lg-5 mt-auto mb-4">
                        @if($banner->title)
                            <h1 class="display-4 fw-bold text-white mb-3 text-shadow">{{ $banner->title }}</h1>
                        @endif
                        
                        @if($banner->description)
                            <p class="lead text-white-50 mb-4 d-none d-md-block text-shadow">{{ $banner->description }}</p>
                        @endif
                        
                        @if($banner->link)
                            <a href="{{ $banner->link }}" class="btn btn-theme btn-lg px-5 rounded-pill shadow-sm">Lihat Penawaran</a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        @endforeach
    </div>
    
    @if($banners->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3 bg-opacity-50" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-3 bg-opacity-50" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    @endif
</div>
@else
<!-- Default Hero if no banners -->
<div class="row align-items-center mb-5 fade-in-up py-4 py-lg-5">
    <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
        <h1 class="font-script display-3 text-dark mb-2">Welcome to <span class="text-warning">Hassan's Koekjes</span></h1>
        <h2 class="fw-bold mb-3 display-4"><span class="brush-accent">Premium Bakery</span></h2>
        <p class="lead text-muted mb-4">Sajian kue kering premium dengan resep otentik pilihan keluarga. Renyah, lezat, dan dibuat dengan sepenuh hati untuk momen spesial Anda.</p>
        <a href="#katalog" class="btn btn-theme btn-lg px-5 shadow-sm rounded-pill">Lihat Menu</a>
    </div>
    <div class="col-lg-6 text-center">
        <div class="bg-white rounded-circle shadow-lg d-flex align-items-center justify-content-center mx-auto" style="width: 300px; height: 300px; border: 8px solid white;">
            <i class="bi bi-shop text-warning" style="font-size: 6rem;"></i>
        </div>
    </div>
</div>
@endif

<!-- Promo Section -->
@if($settings['promo_is_active'] == '1')
<div id="promo" class="mb-5 fade-in-up mt-5 pt-4">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #e65c00 0%, #ff8c42 100%);">
        <div class="row g-0 align-items-center">
            <div class="col-md-8 p-4 p-lg-5 text-white">
                <span class="badge bg-white text-danger rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm">{{ $settings['promo_badge'] }}</span>
                <h2 class="fw-bold mb-3">{{ $settings['promo_title'] }}</h2>
                <p class="lead mb-4 opacity-75" style="font-size: 1.1rem;">{{ $settings['promo_desc'] }}</p>
                <div class="d-flex align-items-center gap-3">
                    <a href="#katalog" class="btn btn-light text-danger fw-bold rounded-pill px-4 shadow">Pesan Sekarang</a>
                    @if($settings['promo_valid_until'])
                        <span class="text-white-50 small"><i class="bi bi-clock"></i> {{ $settings['promo_valid_until'] }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-center position-relative h-100">
                <div class="position-absolute top-50 start-50 translate-middle text-white opacity-25">
                    <i class="bi bi-gift-fill" style="font-size: 12rem;"></i>
                </div>
                <div class="position-relative h-100 d-flex align-items-center justify-content-center p-4">
                    <div class="bg-white rounded-circle shadow-lg d-flex align-items-center justify-content-center flex-column" style="width: 140px; height: 140px; transform: rotate(15deg);">
                        <span class="text-muted small fw-bold">HEMAT HINGGA</span>
                        <h3 class="text-danger fw-bold mb-0">{{ $settings['promo_discount_text'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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

<!-- Link to Catalog Section -->
<div id="katalog" class="fade-in-up py-5 text-center mt-4">
    <div class="bg-light rounded-4 p-5 shadow-sm border mx-auto" style="max-width: 800px;">
        <i class="bi bi-grid-fill text-secondary mb-3 d-block" style="font-size: 3rem;"></i>
        <h3 class="fw-bold mb-3 text-dark">Eksplorasi Seluruh Varian Kue</h3>
        <p class="text-muted mb-4 lead">Dari Wijsman premium, paket Hantaran eksklusif, hingga resep Original keluarga kami. Temukan favorit Anda!</p>
        <a href="{{ route('storefront.catalog') }}" class="btn btn-theme btn-lg px-5 rounded-pill shadow-sm fw-bold">
            Lihat Katalog Lengkap <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</div>
@endsection
