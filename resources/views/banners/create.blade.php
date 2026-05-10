@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('settings.index') }}" class="text-decoration-none text-muted small fw-medium">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Pengaturan Website
    </a>
    <h3 class="fw-bold text-dark mt-2 mb-0">Tambah Banner Baru</h3>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 bg-white">
            <div class="card-body p-4">
                <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Gambar Banner <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control bg-light border-0 @error('image') is-invalid @enderror" accept="image/*" required>
                        <div class="form-text small"><i class="bi bi-info-circle me-1"></i>Gunakan gambar lanskap (16:9) resolusi tinggi untuk hasil maksimal.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Promo (Opsional)</label>
                        <input type="text" name="title" class="form-control bg-light border-0 @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Diskon Lebaran 50%">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" rows="3" class="form-control bg-light border-0 @error('description') is-invalid @enderror" placeholder="Tuliskan detail promosi atau kalimat ajakan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Tautan Aksi / Link (Opsional)</label>
                        <input type="url" name="link" class="form-control bg-light border-0 @error('link') is-invalid @enderror" value="{{ old('link') }}" placeholder="https://...">
                        <div class="form-text small">Tautan yang akan dituju saat pengunjung mengklik banner ini.</div>
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <div class="form-check form-switch d-flex align-items-center">
                            <input class="form-check-input fs-4 mt-0 me-3" type="checkbox" role="switch" name="is_active" id="isActive" checked>
                            <label class="form-check-label fw-bold" for="isActive">Tampilkan Banner di Website</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Banner
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
