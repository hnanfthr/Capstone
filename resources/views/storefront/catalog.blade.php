@extends('layouts.storefront')

@section('content')
<div class="container py-5 fade-in-up mt-4">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-dark mb-2">Katalog Produk</h1>
        <p class="lead text-muted">Jelajahi seluruh varian kue spesial kami, dibuat dengan bahan premium.</p>
    </div>

    @if($productsByCategory->count() > 0)
        <!-- Nav Tabs -->
        <ul class="nav nav-pills justify-content-center mb-5 gap-2" id="katalog-tab" role="tablist">
            @php $i = 0; @endphp
            @foreach($productsByCategory as $category => $products)
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 py-2 fw-bold shadow-sm {{ $i == 0 ? 'active btn-theme text-white' : 'bg-white text-dark border' }}" 
                            id="tab-{{ Str::slug($category) }}" 
                            data-bs-toggle="pill" 
                            data-bs-target="#pane-{{ Str::slug($category) }}" 
                            type="button" role="tab" 
                            aria-controls="pane-{{ Str::slug($category) }}" 
                            aria-selected="{{ $i == 0 ? 'true' : 'false' }}">
                        {{ $category }} <span class="badge bg-light text-dark ms-1 rounded-circle">{{ $products->count() }}</span>
                    </button>
                </li>
                @php $i++; @endphp
            @endforeach
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="katalog-tabContent">
            @php $j = 0; @endphp
            @foreach($productsByCategory as $category => $products)
                <div class="tab-pane fade {{ $j == 0 ? 'show active' : '' }}" 
                     id="pane-{{ Str::slug($category) }}" 
                     role="tabpanel" 
                     aria-labelledby="tab-{{ Str::slug($category) }}" tabindex="0">
                    
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                        @foreach($products as $product)
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
                                            
                                            @php 
                                                $waMessage = "Halo Hassan's Koekjes, saya ingin memesan kue *" . $product->nama . "* (Rp " . number_format($product->harga, 0, ',', '.') . ").";
                                                $waLink = "https://wa.me/" . $waNumber . "?text=" . urlencode($waMessage);
                                            @endphp
                                            <a href="{{ $waLink }}" target="_blank" class="btn btn-success w-100 fw-bold rounded-pill mt-auto" onclick="event.stopPropagation();">
                                                <i class="bi bi-whatsapp"></i> Pre-Order via WA
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @php $j++; @endphp
            @endforeach
        </div>
    @else
        <div class="col-12 text-center py-5">
            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Maaf, belum ada produk yang tersedia saat ini.</h4>
        </div>
    @endif
</div>
@endsection
