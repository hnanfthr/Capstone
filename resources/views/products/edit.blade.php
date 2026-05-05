@extends('layouts.app')

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 600px;">
    <div class="card-header bg-white">
        <h4 class="mb-0">Edit Produk: {{ $product->nama }}</h4>
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

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Kode Produk</label>
                <input type="text" name="kode" class="form-control" value="{{ old('kode', $product->kode) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $product->nama) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga', $product->harga) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Wijsman" {{ old('kategori', $product->kategori) == 'Wijsman' ? 'selected' : '' }}>Wijsman</option>
                    <option value="Segi Panjang" {{ old('kategori', $product->kategori) == 'Segi Panjang' ? 'selected' : '' }}>Segi Panjang</option>
                    <option value="Reguler" {{ old('kategori', $product->kategori) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $product->deskripsi) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Produk Baru (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                @if($product->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/products/' . $product->foto) }}" width="80" class="rounded object-fit-cover">
                        <small class="text-muted d-block mt-1">Foto saat ini</small>
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
