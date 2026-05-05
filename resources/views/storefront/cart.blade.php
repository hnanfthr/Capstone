@extends('layouts.storefront')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">Keranjang Belanja</h2>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Produk</th>
                                    <th scope="col" class="text-center">Harga</th>
                                    <th scope="col" class="text-center">Kuantitas</th>
                                    <th scope="col" class="text-center">Subtotal</th>
                                    <th scope="col" class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                @if($details['image'])
                                                    <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                                <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                            </div>
                                        </td>
                                        <td class="text-center">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $details['quantity'] }}</td>
                                        <td class="text-center fw-bold">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                        <td class="text-center pe-4">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga</span>
                        <span class="fw-bold fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 fw-bold py-2">
                        Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('storefront.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="col-12 text-center py-5">
            <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">Keranjang Anda masih kosong</h4>
            <p class="text-muted mb-4">Yuk, cari kue favorit Anda sekarang!</p>
            <a href="{{ route('storefront.index') }}" class="btn btn-warning fw-bold px-4">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection
