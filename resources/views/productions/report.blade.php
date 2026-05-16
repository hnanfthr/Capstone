@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Form Pencatatan Produksi -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 rounded-4 h-100 bg-white">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam text-primary me-2"></i> Catat Barang Masuk</h5>
                <p class="text-muted small mt-1">Tambahkan stok kue yang baru diproduksi dari dapur.</p>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('productions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="product_id" class="form-select bg-light border-0" required>
                            <option value="">-- Pilih Kue --</option>
                            @foreach($products as $kategori => $groupedProducts)
                                <optgroup label="{{ $kategori }}">
                                    @foreach($groupedProducts as $product)
                                        <option value="{{ $product->id }}">{{ $product->nama }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted small fw-medium">Jumlah (Toples)</label>
                            <input type="number" name="quantity" class="form-control bg-light border-0" min="1" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted small fw-medium">Tanggal Produksi</label>
                            <input type="date" name="production_date" class="form-control bg-light border-0" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control bg-light border-0" rows="2" placeholder="Contoh: Batch pagi"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Simpan & Tambah Stok
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Produksi Berkala -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 bg-white h-100">
            <div class="card-header bg-transparent border-bottom pt-4 pb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text text-success me-2"></i> Riwayat Produksi</h5>
                
                <!-- Filter Form -->
                <form action="{{ route('productions.report') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
                    <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                        <input type="date" name="start_date" class="form-control border-0 bg-light" value="{{ request('start_date') }}">
                        <span class="input-group-text bg-light border-0">-</span>
                        <input type="date" name="end_date" class="form-control border-0 bg-light" value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-dark px-3">Filter</button>
                    </div>
                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('productions.report') }}" class="btn btn-sm btn-outline-danger rounded-3 d-flex align-items-center justify-content-center px-3">Reset</a>
                    @endif
                </form>
            </div>
            
            <div class="card-body p-0">
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Produk</th>
                                <th class="text-center">Jumlah Masuk</th>
                                <th class="pe-4">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productions as $prod)
                            <tr>
                                <td class="ps-4"><span class="text-muted small fw-medium">{{ \Carbon\Carbon::parse($prod->production_date)->format('d M Y') }}</span></td>
                                <td class="fw-bold text-dark">{{ $prod->product ? $prod->product->nama : 'Produk Dihapus' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3">+{{ $prod->quantity }}</span>
                                </td>
                                <td class="pe-4 text-muted small">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $prod->notes ?? '-' }}</span>
                                        <a href="{{ route('productions.print', $prod->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle" title="Cetak Label Batch">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada riwayat produksi di periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-block d-lg-none p-3">
                    @forelse($productions as $prod)
                        <div class="card shadow-sm border-0 mb-3 rounded-4 bg-light bg-opacity-50">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($prod->production_date)->format('d M Y') }}</span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3">+{{ $prod->quantity }} Toples</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2">{{ $prod->product ? $prod->product->nama : 'Produk Dihapus' }}</h6>
                                @if($prod->notes)
                                    <div class="bg-white p-2 rounded-3 small text-muted border mb-2">
                                        <i class="bi bi-info-circle me-1"></i> {{ $prod->notes }}
                                    </div>
                                @endif
                                <a href="{{ route('productions.print', $prod->id) }}" target="_blank" class="btn btn-sm btn-outline-dark w-100 rounded-3">
                                    <i class="bi bi-printer me-1"></i> Cetak Label Expired
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada riwayat produksi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
