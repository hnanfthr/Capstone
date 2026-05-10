@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-sliders text-primary me-2"></i> Pengaturan Website</h3>
        <p class="text-muted small mb-0">Kelola gambar banner slider dan teks promo yang tampil di halaman utama E-Commerce.</p>
    </div>
</div>

<ul class="nav nav-pills mb-4" id="settingsTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner" type="button" role="tab" aria-controls="banner" aria-selected="true">
            <i class="bi bi-images me-1"></i> Slider Banner
        </button>
    </li>
    <li class="nav-item ms-2" role="presentation">
        <button class="nav-link rounded-pill px-4" id="promo-tab" data-bs-toggle="tab" data-bs-target="#promo" type="button" role="tab" aria-controls="promo" aria-selected="false">
            <i class="bi bi-megaphone me-1"></i> Teks Promo Khusus
        </button>
    </li>
</ul>

<div class="tab-content" id="settingsTabContent">
    <!-- Tab 1: Manajemen Banner -->
    <div class="tab-pane fade show active" id="banner" role="tabpanel" aria-labelledby="banner-tab">
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('banners.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Banner
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @forelse($banners as $banner)
            <div class="col">
                <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden bg-white">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner" class="w-100" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($banner->is_active)
                                <span class="badge bg-success shadow-sm rounded-pill"><i class="bi bi-check-circle"></i> Aktif</span>
                            @else
                                <span class="badge bg-secondary shadow-sm rounded-pill"><i class="bi bi-x-circle"></i> Non-Aktif</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold text-dark mb-2">{{ $banner->title ?? 'Tanpa Judul' }}</h5>
                        <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($banner->description, 100) ?? '-' }}</p>
                        
                        @if($banner->link)
                            <div class="mb-3">
                                <a href="{{ $banner->link }}" target="_blank" class="text-primary small text-decoration-none">
                                    <i class="bi bi-link-45deg"></i> {{ Str::limit($banner->link, 30) }}
                                </a>
                            </div>
                        @endif
                        
                        <div class="d-flex gap-2 mt-auto pt-3 border-top">
                            <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-light border text-warning flex-fill rounded-3 fw-medium">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="flex-fill form-delete" data-confirm-message="Yakin ingin menghapus banner ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light border text-danger w-100 rounded-3 fw-medium">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm rounded-4 text-center py-5 bg-white">
                    <i class="bi bi-images fs-1 d-block mb-3 text-info"></i>
                    <h5 class="fw-bold">Belum ada Banner Promosi</h5>
                    <p class="text-muted mb-4">Tambahkan banner untuk menghiasi halaman depan toko online Anda.</p>
                    <a href="{{ route('banners.create') }}" class="btn btn-primary rounded-pill px-4">Buat Banner Pertama</a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Tab 2: Promo Khusus -->
    <div class="tab-pane fade" id="promo" role="tabpanel" aria-labelledby="promo-tab">
        <div class="card shadow-sm border-0 rounded-4 bg-white">
            <div class="card-body p-4">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <div class="form-check form-switch d-flex align-items-center">
                            <input type="hidden" name="promo_is_active" value="0">
                            <input class="form-check-input fs-4 mt-0 me-3" type="checkbox" role="switch" name="promo_is_active" value="1" id="promoActive" {{ $settings['promo_is_active'] == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="promoActive">Tampilkan Blok Promo Khusus di Website</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Label Badge Promo</label>
                            <input type="text" name="promo_badge" class="form-control bg-light border-0" value="{{ $settings['promo_badge'] ?? '' }}" placeholder="SPESIAL MINGGU INI">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Teks Diskon / Highlight Kanan</label>
                            <input type="text" name="promo_discount_text" class="form-control bg-light border-0" value="{{ $settings['promo_discount_text'] ?? '' }}" placeholder="20%">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Utama Promo</label>
                        <input type="text" name="promo_title" class="form-control bg-light border-0 fs-5 fw-bold" value="{{ $settings['promo_title'] ?? '' }}" placeholder="Paket Bundling Keluarga 👨‍👩‍👧‍👦">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Deskripsi Detail</label>
                        <textarea name="promo_desc" rows="3" class="form-control bg-light border-0">{{ $settings['promo_desc'] ?? '' }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Masa Berlaku</label>
                        <input type="text" name="promo_valid_until" class="form-control bg-light border-0" value="{{ $settings['promo_valid_until'] ?? '' }}" placeholder="Berlaku s.d akhir bulan">
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Pengaturan Promo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
