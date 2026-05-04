@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Form Pencatatan Produksi -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Catat Barang Masuk (Dapur)</h5>
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
                <form action="{{ route('productions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Produk</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Produksi</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Produksi</label>
                        <input type="date" name="production_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan & Tambah Stok</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Produksi Berkala -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laporan Produksi</h5>
                
                <!-- Filter Form -->
                <form action="{{ route('productions.report') }}" method="GET" class="d-flex gap-2">
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    <span class="align-self-center">-</span>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                    <a href="{{ route('productions.report') }}" class="btn btn-sm btn-outline-danger">Reset</a>
                </form>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productions as $prod)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($prod->production_date)->format('d M Y') }}</td>
                            <td>{{ $prod->product ? $prod->product->nama : 'Produk Dihapus' }}</td>
                            <td>+{{ $prod->quantity }}</td>
                            <td>{{ $prod->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat produksi di periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
