@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard Stok</h3>
        <p class="text-muted small mb-0">Pantau pergerakan stok (Net Stock & Control) secara real-time.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-danger px-3 py-2 rounded-pill shadow-sm bg-white" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
            <i class="bi bi-dash-circle me-1"></i> Kurangi Stok Manual
        </button>
        <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-inline-flex align-items-center">
            <span class="spinner-grow spinner-grow-sm text-success me-2" role="status" aria-hidden="true"></span>
            <span class="small fw-bold text-muted">Live Update</span>
        </div>
    </div>
</div>

<!-- Modal Kurangi Stok Manual -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-labelledby="adjustStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-danger" id="adjustStockModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Kurangi Stok Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-4">Gunakan fitur ini hanya untuk mengurangi stok di luar pesanan website (misal: pesanan langsung via WhatsApp, barang rusak, tester, dll). Sistem FIFO akan tetap berjalan memotong batch tertua secara otomatis.</p>
                <form action="{{ route('stocks.adjust') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Pilih Kue</label>
                        <select name="product_id" class="form-select bg-light border-0" required>
                            <option value="">-- Pilih --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} (Sisa Stok: {{ $p->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jumlah Toples (Dikurangi)</label>
                        <input type="number" name="quantity" class="form-control bg-light border-0" min="1" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Catatan/Alasan (Opsional)</label>
                        <textarea name="note" class="form-control bg-light border-0" rows="2" placeholder="Contoh: Terjual via WA a.n Budi"></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold">Konfirmasi Pemotongan</button>
                </form>
            </div>
        </div>
    </div>

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
    @forelse($products as $product)
    <div class="col">
        <div class="card shadow-sm border-0 h-100 rounded-4 bg-white overflow-hidden" style="transition: transform 0.3s ease;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" width="45" height="45" class="object-fit-cover rounded-circle shadow-sm border">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted border shadow-sm" style="width: 45px; height: 45px;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="fw-bold text-dark mb-0 lh-1">{{ $product->nama }}</h5>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill mt-1 small">{{ $product->kategori }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center py-3 bg-light rounded-4 mb-3">
                    <h1 class="display-3 fw-bold mb-0 lh-1 {{ $product->stok > 10 ? 'text-success' : ($product->stok > 0 ? 'text-warning' : 'text-danger') }}">
                        {{ $product->stok }}
                    </h1>
                    <p class="text-muted small fw-medium mt-1 mb-0">Net Stock Saat Ini</p>
                </div>
                
                <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-up-short text-success fs-5 lh-1"></i>
                        </div>
                        <span class="text-muted small fw-medium">Produksi Hari Ini</span>
                    </div>
                    <span class="badge bg-success rounded-pill px-3 fs-6">+{{ $todayProductions->get($product->id, 0) }}</span>
                </div>
                
                @if(isset($activeBatches[$product->id]) && count($activeBatches[$product->id]) > 0)
                <div class="bg-light rounded-4 p-3 mb-2 border">
                    <h6 class="fw-bold small text-dark mb-2"><i class="bi bi-diagram-3-fill text-primary me-1"></i> Sistem FIFO Aktif</h6>
                    <div class="d-flex flex-column gap-2">
                        @foreach($activeBatches[$product->id] as $index => $batch)
                            <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded-3 border-start {{ $index == 0 ? 'border-danger border-3 shadow-sm' : 'border-secondary border-2' }}">
                                <div>
                                    <div class="small fw-bold {{ $index == 0 ? 'text-danger' : 'text-dark' }}">Batch {{ \Carbon\Carbon::parse($batch->production_date)->format('d M') }}</div>
                                    <div style="font-size: 0.7rem;" class="text-muted">Exp: {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') }}</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $index == 0 ? 'bg-danger' : 'bg-secondary' }} rounded-pill">{{ $batch->remaining_quantity }} Toples</span>
                                    @if($index == 0)
                                        <div style="font-size: 0.65rem;" class="text-danger fw-bold mt-1">JUAL DULU!</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
            </div>
            <div class="progress bg-light" style="height: 6px; border-radius: 0;">
                @php
                    // Visualisasi sederhana kapasitas
                    $percentage = min(100, ($product->stok / 50) * 100);
                    $bgClass = $product->stok > 10 ? 'bg-success' : ($product->stok > 0 ? 'bg-warning' : 'bg-danger');
                @endphp
                <div class="progress-bar {{ $bgClass }} progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm rounded-4 text-center py-5 bg-white">
            <i class="bi bi-inbox fs-1 d-block mb-3 text-info"></i>
            <h5 class="fw-bold">Belum ada produk yang terdaftar</h5>
            <p class="text-muted">Tambahkan produk pertama Anda untuk mulai memantau stok.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">Tambah Produk Sekarang</a>
        </div>
    </div>
    @endforelse
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
