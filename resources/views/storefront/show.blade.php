@extends('layouts.storefront')

@section('content')
<div class="row fade-in-up mt-4">
    <div class="col-12 mb-3">
        <a href="{{ route('storefront.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>

    <!-- Product Details -->
    <div class="col-lg-5 mb-4">
        @if($product->foto)
            <img src="{{ asset('storage/' . $product->foto) }}" class="img-fluid rounded-4 shadow-sm w-100" alt="{{ $product->nama }}">
        @else
            <div class="bg-white rounded-4 shadow-sm d-flex align-items-center justify-content-center text-muted w-100" style="height: 400px;">
                <i class="bi bi-image" style="font-size: 5rem;"></i>
            </div>
        @endif
    </div>

    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 p-lg-5">
                <span class="badge badge-theme mb-2">{{ $product->kategori }}</span>
                <h2 class="fw-bold mb-2 text-dark">{{ $product->nama }}</h2>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="star-rating fs-5 me-2">
                        @php $rating = round($product->average_rating); @endphp
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="text-muted">({{ $product->reviews->count() }} Ulasan)</span>
                </div>

                <h3 class="fw-bold text-warning mb-4">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
                
                <h6 class="fw-bold text-dark mb-2">Deskripsi Produk</h6>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    {{ $product->deskripsi ?? 'Belum ada deskripsi untuk produk ini.' }}
                </p>

                <div class="alert alert-light border border-success d-flex flex-column align-items-center mb-4 p-4 text-center">
                    <i class="bi bi-clock-history text-success mb-2" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold text-success mb-1">Pre-Order Tersedia</h5>
                    <p class="text-muted small mb-0">Produk ini dipesan dengan sistem Made by Order (Fresh from oven).</p>
                </div>

                @php 
                    $waMessage = "Halo Hassan's Koekjes, saya ingin memesan kue *" . $product->nama . "* (Rp " . number_format($product->harga, 0, ',', '.') . ").";
                    $waLink = "https://wa.me/" . $waNumber . "?text=" . urlencode($waMessage);
                @endphp
                
                <a href="{{ $waLink }}" target="_blank" class="btn btn-success btn-lg w-100 fw-bold rounded-pill mb-3">
                    <i class="bi bi-whatsapp me-2"></i> Pre-Order via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Section -->
<div class="row fade-in-up mt-5">
    <div class="col-12">
        <h3 class="font-script fw-bold text-dark mb-4 text-center">Apa Kata Mereka?</h3>
    </div>
    
    <div class="col-lg-8 mx-auto">
        <!-- Form Review -->
        <div class="card border-0 shadow-sm rounded-4 mb-5 bg-white">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square text-warning me-2"></i> Tulis Ulasan Anda</h5>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Nama Anda</label>
                            <input type="text" name="customer_name" class="form-control bg-light border-0" required placeholder="Contoh: Budi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Rating (1-5 Bintang)</label>
                            <select name="rating" class="form-select bg-light border-0" required>
                                <option value="5">5 - Sangat Enak! 🌟🌟🌟🌟🌟</option>
                                <option value="4">4 - Enak 🌟🌟🌟🌟</option>
                                <option value="3">3 - Lumayan 🌟🌟🌟</option>
                                <option value="2">2 - Kurang 🌟🌟</option>
                                <option value="1">1 - Sangat Kurang 🌟</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Komentar (Opsional)</label>
                        <textarea name="comment" rows="3" class="form-control bg-light border-0" placeholder="Kue nya renyah dan lumer di mulut..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-pill">Kirim Ulasan</button>
                </form>
            </div>
        </div>

        <!-- List of Reviews -->
        <h5 class="fw-bold mb-4">Ulasan Pembeli ({{ $product->reviews->count() }})</h5>
        
        @forelse($product->reviews()->latest()->get() as $review)
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-dark">{{ $review->customer_name }}</h6>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="star-rating small mb-2">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    @if($review->comment)
                        <p class="text-muted mb-0" style="font-style: italic;">"{{ $review->comment }}"</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-chat-left-heart text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted">Belum ada ulasan untuk kue ini. Jadilah yang pertama memberikan ulasan!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
