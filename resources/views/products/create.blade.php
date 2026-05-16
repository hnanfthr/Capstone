@extends('layouts.app')

@section('content')
<div class="card shadow-sm max-w-lg mx-auto" style="max-width: 600px;">
    <div class="card-header bg-white">
        <h4 class="mb-0">Tambah Produk</h4>
    </div>
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Kode Produk</label>
                <input type="text" name="kode" class="form-control" value="{{ old('kode') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select premium-select" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Wijsman" {{ old('kategori') == 'Wijsman' ? 'selected' : '' }}>Wijsman</option>
                    <option value="Hantaran" {{ old('kategori') == 'Hantaran' ? 'selected' : '' }}>Hantaran</option>
                    <option value="Original" {{ old('kategori') == 'Original' ? 'selected' : '' }}>Original</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Produk (Opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
