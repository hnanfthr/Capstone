<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('storefront.index')->with('error', 'Keranjang belanja Anda kosong.');
        }
        return view('storefront.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('storefront.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            $order = Order::create([
                'customer_name' => $request->customer_name,
                'whatsapp_number' => $request->whatsapp_number,
                'address' => $request->address,
                'total_price' => $totalPrice,
                'status' => 'Pending',
            ]);

            $orderItemsText = "";
            foreach ($cart as $id => $item) {
                $product = Product::lockForUpdate()->find($id);
                
                if (!$product || $product->stok < $item['quantity']) {
                    throw new \Exception("Stok produk {$item['name']} tidak mencukupi (sisa: " . ($product ? $product->stok : 0) . ").");
                }
                
                // Potong stok utama product
                $product->decrement('stok', $item['quantity']);

                // FIFO: Potong dari batch produksi tertua yang masih ada sisa stok
                $qtyToDeduct = $item['quantity'];
                $batches = \App\Models\Production::where('product_id', $id)
                            ->where('remaining_quantity', '>', 0)
                            ->orderBy('production_date', 'asc')
                            ->orderBy('id', 'asc')
                            ->get();

                foreach ($batches as $batch) {
                    if ($qtyToDeduct <= 0) break;
                    
                    if ($batch->remaining_quantity >= $qtyToDeduct) {
                        $batch->decrement('remaining_quantity', $qtyToDeduct);
                        $qtyToDeduct = 0;
                    } else {
                        $deducted = $batch->remaining_quantity;
                        $batch->update(['remaining_quantity' => 0]);
                        $qtyToDeduct -= $deducted;
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                $orderItemsText .= "- {$item['name']} ({$item['quantity']}x) - Rp " . number_format($item['price'] * $item['quantity'], 0, ',', '.') . "%0A";
            }

            DB::commit();
            session()->forget('cart');

            // Nomor WA Admin (Berdasarkan request)
            $adminPhone = '6287759824300';
            
            // Format Pesan WhatsApp
            $totalFormatted = number_format($totalPrice, 0, ',', '.');
            $message = "Halo Hassans Keuken!%0A%0ASaya ingin memesan kue:%0A%0A*ID Pesanan:* {$order->order_no}%0A*Nama:* {$order->customer_name}%0A*No WA:* {$order->whatsapp_number}%0A*Alamat:* {$order->address}%0A%0A*Rincian Pesanan:*%0A{$orderItemsText}%0A*Total Pembayaran:* Rp {$totalFormatted}%0A%0AMohon info ketersediaan dan ongkos kirim. Terima kasih!";
            
            $waLink = "https://wa.me/{$adminPhone}?text={$message}";

            return view('storefront.success', compact('order', 'waLink'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }
}
