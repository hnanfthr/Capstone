@extends('layouts.storefront')

@section('content')
<div class="container py-5 fade-in-up mt-4">
    <div class="text-center mb-5">
        <h1 class="display-3 fw-bold text-dark mb-2 font-script">Katalog Produk</h1>
        <p class="lead text-muted">Jelajahi seluruh varian kue spesial kami, dibuat dengan bahan premium.</p>
    </div>

    <!-- Filter & Search Form -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <form action="{{ route('storefront.catalog') }}" method="GET" class="card border-0 shadow-sm rounded-pill p-2">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select name="category" class="form-select border-0 bg-transparent fw-bold text-dark px-4" onchange="this.form.submit()">
                            <option value="all">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 border-start">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-muted ps-4"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Cari nama kue..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-theme rounded-pill w-100 fw-bold">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($products->count() > 0)
        <!-- Product Grid -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4 mb-5">
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
                                    <i class="bi bi-whatsapp"></i> Pre-Order
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="col-12 text-center py-5">
            <i class="bi bi-search text-muted mb-3 d-block" style="font-size: 4rem;"></i>
            <h4 class="text-muted">Kue tidak ditemukan.</h4>
            <p class="text-muted mb-4">Silakan coba kata kunci lain atau pilih kategori yang berbeda.</p>
            <a href="{{ route('storefront.catalog') }}" class="btn btn-outline-dark rounded-pill px-4">Tampilkan Semua Kue</a>
        </div>
    @endif
</div>
@endsection
