<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Production;
use Illuminate\Support\Facades\DB;

class StockDashboardController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        $todayProductions = Production::whereDate('production_date', today())
            ->select('product_id', DB::raw('SUM(quantity) as total_produced'))
            ->groupBy('product_id')
            ->pluck('total_produced', 'product_id');

        // FIFO: Ambil batch yang masih ada sisa stok, diurutkan dari terlama
        $activeBatches = Production::where('remaining_quantity', '>', 0)
            ->orderBy('production_date', 'asc')
            ->get()
            ->groupBy('product_id');

        return view('stocks.dashboard', compact('products', 'todayProductions', 'activeBatches'));
    }

    public function adjust(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $product = Product::lockForUpdate()->find($validated['product_id']);
            
            if ($product->stok < $validated['quantity']) {
                throw new \Exception("Stok tidak mencukupi untuk dikurangi sejumlah tersebut.");
            }

            // Potong stok utama
            $product->decrement('stok', $validated['quantity']);

            // FIFO: Potong dari batch tertua
            $qtyToDeduct = $validated['quantity'];
            $batches = Production::where('product_id', $product->id)
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

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', 'Stok berhasil dikurangi secara manual.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengurangi stok: ' . $e->getMessage());
        }
    }
}
