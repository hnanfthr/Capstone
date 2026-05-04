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

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // Auto-update stock logic
        if ($validated['status'] === 'Selesai' && $oldStatus !== 'Selesai') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stok', $item->quantity);
                }
            }
        } elseif ($oldStatus === 'Selesai' && $validated['status'] !== 'Selesai') {
            // Restore stock if status changed back from Selesai
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stok', $item->quantity);
                }
            }
        }

        return redirect()->route('orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
