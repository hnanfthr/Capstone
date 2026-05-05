@extends('layouts.storefront')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4 text-center">
                <h3 class="fw-bold mb-3"><i class="bi bi-search"></i> Lacak Pesanan Saya</h3>
                <p class="text-muted mb-4">Masukkan Nomor Pesanan (Contoh: HK-20231015-0001) atau Nomor WhatsApp Anda untuk melihat status pesanan terkini.</p>
                
                <form action="{{ route('storefront.trackOrder') }}" method="POST" class="d-flex mx-auto" style="max-width: 500px;">
                    @csrf
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="search" placeholder="No. Pesanan / No. WhatsApp" value="{{ $search ?? '' }}" required>
                        <button class="btn btn-warning fw-bold" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($search))
            <h5 class="fw-bold mb-3">Hasil Pencarian untuk: <span class="text-primary">"{{ $search }}"</span></h5>
            
            @if(isset($orders) && $orders->count() > 0)
                @foreach($orders as $order)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold">No. Pesanan: {{ $order->order_no }}</span>
                            @php
                                $badgeClass = 'secondary';
                                if($order->status == 'Pending') $badgeClass = 'warning text-dark';
                                if($order->status == 'Sudah Diambil') $badgeClass = 'info text-dark';
                                if($order->status == 'Dikirim') $badgeClass = 'primary';
                                if($order->status == 'Selesai') $badgeClass = 'success';
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} px-3 py-2 fs-6">{{ $order->status }}</span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <p class="mb-1 text-muted small">Nama Pelanggan</p>
                                    <p class="fw-bold mb-0">{{ $order->customer_name }}</p>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <p class="mb-1 text-muted small">Tanggal Pesanan</p>
                                    <p class="fw-bold mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6 class="fw-bold mb-3">Rincian Item</h6>
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($order->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            {{ $item->product ? $item->product->nama : 'Produk Tidak Tersedia' }}
                                            <span class="text-muted ms-1">x{{ $item->quantity }}</span>
                                        </div>
                                        <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                                <span class="fw-bold">Total Pembayaran</span>
                                <span class="fw-bold fs-5 text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning text-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-4 d-block mb-2"></i>
                    Maaf, kami tidak dapat menemukan pesanan dengan nomor tersebut. Silakan periksa kembali dan pastikan format pengetikan sudah benar.
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
