@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark">Manajemen Pesanan</h3>
</div>

<!-- Desktop Table View (Hidden on mobile) -->
<div class="card shadow-sm border-0 d-none d-lg-block mb-4">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted">
                <tr>
                    <th class="ps-4">No. Order</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Item Pesanan</th>
                    <th>Status</th>
                    <th class="pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="ps-4"><span class="fw-bold text-primary">{{ $order->order_no }}</span></td>
                    <td>
                        <div class="fw-bold">{{ $order->customer_name }}</div>
                        @if($order->whatsapp_number)
                            <small class="text-muted d-block"><i class="bi bi-whatsapp"></i> {{ $order->whatsapp_number }}</small>
                        @endif
                        @if($order->address)
                            <small class="text-muted d-block"><i class="bi bi-geo-alt"></i> {{ Str::limit($order->address, 30) }}</small>
                        @endif
                    </td>
                    <td class="fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <ul class="mb-0 ps-3 small text-muted">
                            @foreach($order->items as $item)
                                <li>{{ $item->product ? $item->product->nama : 'Produk Dihapus' }} <span class="fw-bold">(x{{ $item->quantity }})</span></li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @php
                            $badgeClass = 'secondary';
                            if($order->status == 'Pending') $badgeClass = 'warning text-dark';
                            if($order->status == 'Sudah Diambil') $badgeClass = 'info text-dark';
                            if($order->status == 'Dikirim') $badgeClass = 'primary';
                            if($order->status == 'Selesai') $badgeClass = 'success';
                        @endphp
                        <span class="badge bg-{{ $badgeClass }} rounded-pill">{{ $order->status }}</span>
                    </td>
                    <td class="pe-4">
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm border-0 bg-light" required>
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Sudah Diambil" {{ $order->status == 'Sudah Diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary rounded-3"><i class="bi bi-check2"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada pesanan masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View (Hidden on desktop) -->
<div class="d-block d-lg-none">
    @forelse($orders as $order)
        <div class="card shadow-sm border-0 mb-3 rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-primary">{{ $order->order_no }}</span>
                    @php
                        $badgeClass = 'secondary';
                        if($order->status == 'Pending') $badgeClass = 'warning text-dark';
                        if($order->status == 'Sudah Diambil') $badgeClass = 'info text-dark';
                        if($order->status == 'Dikirim') $badgeClass = 'primary';
                        if($order->status == 'Selesai') $badgeClass = 'success';
                    @endphp
                    <span class="badge bg-{{ $badgeClass }} rounded-pill">{{ $order->status }}</span>
                </div>
                
                <h5 class="fw-bold mb-1">{{ $order->customer_name }}</h5>
                @if($order->whatsapp_number)
                    <div class="text-muted small mb-1"><i class="bi bi-whatsapp"></i> {{ $order->whatsapp_number }}</div>
                @endif
                @if($order->address)
                    <div class="text-muted small mb-3"><i class="bi bi-geo-alt"></i> {{ $order->address }}</div>
                @endif
                
                <div class="bg-light p-3 rounded-3 mb-3">
                    <div class="fw-bold small mb-2 text-dark">Rincian Pesanan:</div>
                    <ul class="mb-0 ps-3 small text-muted">
                        @foreach($order->items as $item)
                            <li>{{ $item->product ? $item->product->nama : 'Produk Dihapus' }} <span class="fw-bold">(x{{ $item->quantity }})</span></li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3">
                    <span class="text-muted small">Total Pembayaran:</span>
                    <span class="fw-bold text-success fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>

                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select border-0 bg-light" required>
                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Sudah Diambil" {{ $order->status == 'Sudah Diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                        <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="btn btn-primary px-3 rounded-3">Update</button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p>Belum ada pesanan masuk.</p>
        </div>
    @endforelse
</div>
@endsection
