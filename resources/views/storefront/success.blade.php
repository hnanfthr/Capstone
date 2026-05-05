@extends('layouts.storefront')

@section('content')
<div class="row justify-content-center text-center mt-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow border-0 py-5">
            <div class="card-body">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                <h2 class="fw-bold mt-4 mb-3 text-success">Pesanan Berhasil Dibuat!</h2>
                
                <div class="alert alert-light border mb-4 text-start">
                    <p class="mb-1"><strong>ID Pesanan:</strong> <span class="badge bg-secondary">{{ $order->order_no }}</span></p>
                    <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>Total Tagihan:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                
                <p class="text-muted mb-4">
                    Pesanan Anda telah tercatat dalam sistem kami dengan status <strong>Pending</strong>.<br>
                    Untuk memproses pesanan dan mengetahui ongkos kirim, silakan kirimkan format pesanan ini ke WhatsApp Admin kami.
                </p>
                
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-lg fw-bold px-4 rounded-pill shadow-sm">
                    <i class="bi bi-whatsapp me-2"></i> Kirim Pesanan via WhatsApp
                </a>
                
                <div class="mt-4">
                    <a href="{{ route('storefront.index') }}" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
