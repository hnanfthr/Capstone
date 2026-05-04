@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2>Dashboard Posisi Stok Real-time</h2>
    <p class="text-muted">Pantau pergerakan stok (Net Stock & Control) untuk hari ini.</p>
</div>

<div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">{{ $product->nama }}</h5>
                    <span class="badge bg-secondary">{{ $product->kategori }}</span>
                </div>
                
                <h1 class="display-4 text-center {{ $product->stok > 10 ? 'text-success' : ($product->stok > 0 ? 'text-warning' : 'text-danger') }}">
                    {{ $product->stok }}
                </h1>
                <p class="text-center text-muted mb-4">Net Stock Saat Ini</p>
                
                <hr>
                
                <div class="d-flex justify-content-between text-sm">
                    <span><strong>Diproduksi Hari Ini:</strong></span>
                    <span class="text-success">+{{ $todayProductions->get($product->id, 0) }} item</span>
                </div>
                
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="progress" style="height: 5px;">
                    @php
                        // Contoh visualisasi sederhana: max kapasitas asumsi 100
                        $percentage = min(100, ($product->stok / 100) * 100);
                        $bgClass = $product->stok > 10 ? 'bg-success' : 'bg-danger';
                    @endphp
                    <div class="progress-bar {{ $bgClass }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            Belum ada produk yang terdaftar. <a href="{{ route('products.create') }}" class="alert-link">Tambah Produk Sekarang</a>.
        </div>
    </div>
    @endforelse
</div>
@endsection
