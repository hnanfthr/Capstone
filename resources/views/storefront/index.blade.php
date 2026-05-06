@extends('layouts.storefront')

@section('content')
<!-- Hero Section -->
<div class="row align-items-center mb-5 fade-in-up py-4 py-lg-5">
    <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
        <span class="badge bg-danger text-white rounded-pill px-3 py-2 mb-3 shadow-sm"><i class="bi bi-stars"></i> Koleksi Spesial Lebaran</span>
        <h1 class="font-script display-3 text-dark mb-2">Open Pre-Order <span class="text-warning">{{ date('Y') }}</span></h1>
        <h2 class="fw-bold mb-3 display-4"><span class="brush-accent">Hassan's Koekjes</span></h2>
        <p class="lead text-muted mb-4">Sajian kue kering premium dengan resep otentik pilihan keluarga. Renyah, lezat, dan dibuat dengan sepenuh hati untuk momen spesial Anda.</p>
        <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
            <a href="#katalog" class="btn btn-theme btn-lg px-5 shadow-sm rounded-pill">Lihat Menu</a>
            <a href="#promo" class="btn btn-outline-warning btn-lg px-4 shadow-sm rounded-pill fw-bold bg-white text-warning">Promo Hari Ini</a>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Hero Banner Layout -->
        <div class="position-relative">
            <div class="position-absolute top-0 end-0 bg-warning rounded-circle opacity-25" style="width: 200px; height: 200px; filter: blur(40px); z-index: -1;"></div>
            <div class="position-absolute bottom-0 start-0 bg-danger rounded-circle opacity-25" style="width: 150px; height: 150px; filter: blur(40px); z-index: -1;"></div>
            
            <div class="row g-3">
                @php 
                    // Ambil maksimal 3 produk untuk showcase hero
                    $heroProducts = $products->take(3);
                @endphp
                
                @if($heroProducts->count() >= 1 && $heroProducts[0]->foto)
                    <!-- Jika ada foto produk -->
                    <div class="col-8">
                        <img src="{{ asset('storage/' . $heroProducts[0]->foto) }}" class="img-fluid rounded-4 shadow" alt="Kue Premium" style="height: 350px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-4 d-flex flex-column gap-3">
                        @if(isset($heroProducts[1]) && $heroProducts[1]->foto)
                            <img src="{{ asset('storage/' . $heroProducts[1]->foto) }}" class="img-fluid rounded-4 shadow" alt="Kue 2" style="height: 165px; width: 100%; object-fit: cover;">
                        @endif
                        @if(isset($heroProducts[2]) && $heroProducts[2]->foto)
                            <img src="{{ asset('storage/' . $heroProducts[2]->foto) }}" class="img-fluid rounded-4 shadow" alt="Kue 3" style="height: 165px; width: 100%; object-fit: cover;">
                        @endif
                    </div>
                @else
                    <!-- Fallback jika belum ada foto produk sama sekali -->
                    <div class="col-12">
                        <div class="bg-white rounded-4 shadow-sm p-5 text-center border border-warning border-opacity-25" style="background-image: radial-gradient(circle at top right, rgba(230, 92, 0, 0.05), transparent 300px);">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-4 shadow-sm" style="width: 120px; height: 120px;">
                                <i class="bi bi-shop font-script" style="font-size: 3.5rem;"></i>
                            </div>
                            <h3 class="font-script text-dark fw-bold mb-0">Hassan's Premium Bakery</h3>
                            <p class="text-muted small mt-2">Dibuat fresh setiap hari dari oven kami.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Floating Badge -->
            <div class="position-absolute bottom-0 start-50 translate-middle-x mb-n4 shadow-lg bg-white px-4 py-3 rounded-pill text-center d-flex align-items-center border border-warning border-opacity-25">
                <div class="me-3 border-end pe-3">
                    <h4 class="mb-0 fw-bold text-dark">100%</h4>
                    <span class="small text-muted">Halal & Higienis</span>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-warning"><i class="bi bi-star-fill"></i> 4.9</h4>
                    <span class="small text-muted">Rating Pelanggan</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Promo Section -->
<div id="promo" class="mb-5 fade-in-up mt-5 pt-4">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #e65c00 0%, #ff8c42 100%);">
        <div class="row g-0 align-items-center">
            <div class="col-md-8 p-4 p-lg-5 text-white">
                <span class="badge bg-white text-danger rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm">SPESIAL MINGGU INI</span>
                <h2 class="fw-bold mb-3">Paket Bundling Keluarga 👨‍👩‍👧‍👦</h2>
                <p class="lead mb-4 opacity-75" style="font-size: 1.1rem;">Beli 3 toples jenis apa saja, dapatkan potongan harga spesial dan <strong class="text-white">Gratis Kartu Ucapan Premium</strong> untuk orang tersayang.</p>
                <div class="d-flex align-items-center gap-3">
                    <a href="#katalog" class="btn btn-light text-danger fw-bold rounded-pill px-4 shadow">Pesan Sekarang</a>
                    <span class="text-white-50 small"><i class="bi bi-clock"></i> Berlaku s.d akhir bulan</span>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-center position-relative h-100">
                <div class="position-absolute top-50 start-50 translate-middle text-white opacity-25">
                    <i class="bi bi-gift-fill" style="font-size: 12rem;"></i>
                </div>
                <div class="position-relative h-100 d-flex align-items-center justify-content-center p-4">
                    <div class="bg-white rounded-circle shadow-lg d-flex align-items-center justify-content-center flex-column" style="width: 140px; height: 140px; transform: rotate(15deg);">
                        <span class="text-muted small fw-bold">HEMAT HINGGA</span>
                        <h3 class="text-danger fw-bold mb-0">20%</h3>
                    </div>
                </div>
            </div>
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
