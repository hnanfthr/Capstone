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
        $orders = Order::with('items.product')->where('status', '!=', 'Selesai')->latest()->get();
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

    public function create()
    {
        $products = \App\Models\Product::orderBy('nama')->get()->groupBy('kategori');
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|string',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'total_price' => 'required|numeric'
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Generate Order No
            $orderNo = 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));

            // Create Order
            $order = Order::create([
                'order_no' => $orderNo,
                'customer_name' => $request->customer_name,
                'whatsapp_number' => $request->whatsapp_number,
                'address' => $request->address,
                'status' => $request->status,
                'total_price' => $request->total_price,
            ]);

            // Add Order Items & Reduce Stock
            foreach ($request->product_id as $index => $productId) {
                if (!$productId) continue; // Skip empty rows
                
                $product = \App\Models\Product::find($productId);
                $qty = $request->quantity[$index];
                
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->harga,
                ]);

                // Pengurangan Stok Otomatis (sesuai request)
                $product->decrement('stok', $qty);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('orders.index')->with('success', 'Pesanan manual berhasil dibuat dan stok otomatis dikurangi.');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $query = Order::with('items.product')->where('status', 'Selesai')->latest('updated_at');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('updated_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('orders.history', compact('orders'));
    }

    public function export(Request $request)
    {
        $query = Order::with('items.product')->where('status', 'Selesai')->latest('updated_at');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('updated_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $orders = $query->get();

        $filename = "arsip_pesanan_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($orders) {
            $file = fopen('php://output', 'w');
            
            // Kolom header CSV
            fputcsv($file, ['No. Order', 'Tanggal Selesai', 'Nama Pelanggan', 'No WA', 'Total Pembayaran', 'Rincian Produk']);

            foreach ($orders as $row) {
                $rincian = [];
                foreach ($row->items as $item) {
                    $nama = $item->product ? $item->product->nama : 'Produk Dihapus';
                    $rincian[] = "{$nama} (x{$item->quantity})";
                }

                fputcsv($file, [
                    $row->order_no,
                    \Carbon\Carbon::parse($row->updated_at)->format('Y-m-d H:i'),
                    $row->customer_name,
                    $row->whatsapp_number ?? '-',
                    $row->total_price,
                    implode(', ', $rincian)
                ]);
            }

            fclose($file);
        };

        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
    }
}
