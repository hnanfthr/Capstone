@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('settings.index') }}" class="text-decoration-none text-muted small fw-medium">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Pengaturan Website
    </a>
    <h3 class="fw-bold text-dark mt-2 mb-0">Edit Banner</h3>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 bg-white">
            <div class="card-body p-4">
                <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4 text-center">
                        <img src="{{ asset('storage/' . $banner->image) }}" class="img-fluid rounded-4 shadow-sm mb-3" style="max-height: 250px; object-fit: cover; width: 100%;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Ganti Gambar Banner (Opsional)</label>
                        <input type="file" name="image" class="form-control bg-light border-0 @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text small"><i class="bi bi-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah gambar saat ini.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Promo (Opsional)</label>
                        <input type="text" name="title" class="form-control bg-light border-0 @error('title') is-invalid @enderror" value="{{ old('title', $banner->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" rows="3" class="form-control bg-light border-0 @error('description') is-invalid @enderror">{{ old('description', $banner->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Tautan Aksi / Link (Opsional)</label>
                        <input type="url" name="link" class="form-control bg-light border-0 @error('link') is-invalid @enderror" value="{{ old('link', $banner->link) }}">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <div class="form-check form-switch d-flex align-items-center">
                            <input class="form-check-input fs-4 mt-0 me-3" type="checkbox" role="switch" name="is_active" id="isActive" {{ $banner->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">Tampilkan Banner di Website</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
