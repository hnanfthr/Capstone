@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Manajemen Pesanan (Ledger)</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No. Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Total Harga</th>
                    <th>Item Pesanan</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_no }}</strong></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <ul class="mb-0 ps-3">
                            @foreach($order->items as $item)
                                <li>{{ $item->product ? $item->product->nama : 'Produk Dihapus' }} (x{{ $item->quantity }})</li>
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
                        <span class="badge bg-{{ $badgeClass }}">{{ $order->status }}</span>
                    </td>
                    <td>
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Sudah Diambil" {{ $order->status == 'Sudah Diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-dark">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">Belum ada pesanan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
