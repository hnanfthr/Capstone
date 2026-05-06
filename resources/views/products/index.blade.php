@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <h3 class="fw-bold text-dark mb-0">Katalog Produk</h3>
    <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
</div>

<!-- Desktop Table View -->
<div class="card shadow-sm border-0 d-none d-lg-block mb-4">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted">
                <tr>
                    <th class="ps-4">Foto</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="ps-4">
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" width="45" height="45" class="object-fit-cover rounded-3 shadow-sm">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 45px; height: 45px;">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td><span class="text-muted small fw-medium">{{ $product->kode }}</span></td>
                    <td class="fw-bold text-dark">{{ $product->nama }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill">{{ $product->kategori }}</span></td>
                    <td class="fw-bold text-success">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td>
                        @if($product->stok > 0)
                            <span class="badge bg-success rounded-pill px-3">{{ $product->stok }}</span>
                        @else
                            <span class="badge bg-danger rounded-pill px-3">Habis</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        <div class="btn-group shadow-sm rounded-3">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                <i class="bi bi-pencil text-warning"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline form-delete" data-confirm-message="Yakin ingin menghapus produk ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada data produk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="d-block d-lg-none">
    @forelse($products as $product)
        <div class="card shadow-sm border-0 mb-3 rounded-4 overflow-hidden">
            <div class="d-flex align-items-center p-3 border-bottom">
                @if($product->foto)
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" width="60" height="60" class="object-fit-cover rounded-3 shadow-sm me-3">
                @else
                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted me-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-image fs-4"></i>
                    </div>
                @endif
                <div>
                    <span class="text-muted small d-block mb-1">{{ $product->kode }}</span>
                    <h6 class="fw-bold mb-1 text-dark">{{ $product->nama }}</h6>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill small">{{ $product->kategori }}</span>
                </div>
            </div>
            
            <div class="card-body bg-light bg-opacity-50">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted d-block">Harga</small>
                        <span class="fw-bold text-success fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block mb-1">Stok Tersedia</small>
                        @if($product->stok > 0)
                            <span class="badge bg-success rounded-pill px-3">{{ $product->stok }}</span>
                        @else
                            <span class="badge bg-danger rounded-pill px-3">Habis</span>
                        @endif
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning w-50 rounded-3 text-dark fw-medium">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="w-50 form-delete" data-confirm-message="Yakin ingin menghapus produk ini?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-3 fw-medium">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p>Belum ada data produk.</p>
        </div>
    @endforelse
</div>
@endsection
