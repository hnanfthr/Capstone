@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-archive text-primary me-2"></i> Arsip Pesanan</h3>
        <p class="text-muted small mb-0">Lihat histori pesanan yang telah selesai dan unduh laporannya.</p>
    </div>
    <div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark rounded-pill fw-bold px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan Aktif
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4 bg-white mb-4">
    <div class="card-header bg-transparent border-bottom pt-4 pb-3">
        <form action="{{ route('orders.history') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-auto">
                <label class="form-label text-muted small fw-bold mb-0">Tanggal Selesai:</label>
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                    <input type="text" name="start_date" class="form-control border-0 bg-light premium-date" value="{{ request('start_date') }}" placeholder="Start Date" required>
                </div>
            </div>
            <div class="col-md-auto text-center text-muted">s/d</div>
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                    <input type="text" name="end_date" class="form-control border-0 bg-light premium-date" value="{{ request('end_date') }}" placeholder="End Date" required>
                </div>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3">Filter</button>
                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('orders.history') }}" class="btn btn-outline-danger btn-sm rounded-3 px-3 ms-1">Reset</a>
                @endif
            </div>
            
            <div class="col-md text-md-end mt-3 mt-md-0">
                <a href="{{ route('orders.export', request()->all()) }}" class="btn btn-success btn-sm rounded-3 px-4 fw-bold">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel (CSV)
                </a>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="ps-4">No. Order</th>
                        <th>Tanggal Selesai</th>
                        <th>Pelanggan</th>
                        <th>Item Pesanan</th>
                        <th class="text-end pe-4">Total Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4"><span class="fw-bold text-primary">{{ $order->order_no }}</span></td>
                        <td><span class="text-muted small fw-medium">{{ \Carbon\Carbon::parse($order->updated_at)->format('d M Y H:i') }}</span></td>
                        <td>
                            <div class="fw-bold">{{ $order->customer_name }}</div>
                            @if($order->whatsapp_number)
                                <small class="text-muted d-block"><i class="bi bi-whatsapp"></i> {{ $order->whatsapp_number }}</small>
                            @endif
                        </td>
                        <td>
                            <ul class="mb-0 ps-3 small text-muted">
                                @foreach($order->items as $item)
                                    <li>{{ $item->product ? $item->product->nama : 'Produk Dihapus' }} <span class="fw-bold">(x{{ $item->quantity }})</span></li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-end pe-4">
                            <span class="text-success fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada pesanan yang diarsipkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="d-block d-lg-none p-3">
            @forelse($orders as $order)
                <div class="card shadow-sm border-0 mb-3 rounded-4 bg-light bg-opacity-50">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary">{{ $order->order_no }}</span>
                            <span class="badge bg-success rounded-pill">Selesai</span>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $order->customer_name }}</h6>
                        <div class="text-muted small mb-3"><i class="bi bi-calendar-check me-1"></i> Selesai pada: {{ \Carbon\Carbon::parse($order->updated_at)->format('d M Y H:i') }}</div>
                        
                        <div class="bg-white p-3 rounded-3 mb-3 border">
                            <ul class="mb-0 ps-3 small text-muted">
                                @foreach($order->items as $item)
                                    <li>{{ $item->product ? $item->product->nama : 'Produk Dihapus' }} <span class="fw-bold">(x{{ $item->quantity }})</span></li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total:</span>
                            <span class="fw-bold text-success fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada pesanan yang diarsipkan.
                </div>
            @endforelse
        </div>
    </div>
    
    @if($orders->hasPages())
    <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
