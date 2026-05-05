<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Menampilkan Halaman List Pesanan Masuk (Order Ledger)
     */
    public function index()
    {
        $orders = Order::with('items.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Fitur Update Status Pesanan
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Sudah Diambil,Dikirim,Selesai',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
