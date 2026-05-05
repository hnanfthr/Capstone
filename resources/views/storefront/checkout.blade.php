@extends('layouts.storefront')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 mb-4">
        <h3 class="fw-bold mb-4">Formulir Checkout</h3>
        
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Pengiriman</h5>
                    
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required placeholder="Masukkan nama lengkap Anda">
                    </div>
                    
                    <div class="mb-3">
                        <label for="whatsapp_number" class="form-label fw-bold">Nomor WhatsApp Aktif</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">+62</span>
                            <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" required placeholder="81234567890">
                        </div>
                        <small class="text-muted">Kami akan mengirimkan detail pesanan ke nomor ini.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="form-label fw-bold">Alamat Pengiriman Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos..."></textarea>
                    </div>
                    
                    <h5 class="fw-bold mb-3 border-bottom pb-2 mt-5">Ringkasan Keranjang</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr>
                                        <td>{{ $details['name'] }}</td>
                                        <td class="text-center">x{{ $details['quantity'] }}</td>
                                        <td class="text-end">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td colspan="2" class="fw-bold pt-3 fs-5">Total Pembayaran</td>
                                    <td class="text-end fw-bold text-success pt-3 fs-5">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">
                            <i class="bi bi-whatsapp me-2"></i> Buat Pesanan Sekarang
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Kembali ke Keranjang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
