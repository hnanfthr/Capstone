<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Product;

class ProductionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'production_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['remaining_quantity'] = $validated['quantity'];
        $validated['expiry_date'] = \Carbon\Carbon::parse($validated['production_date'])->addDays(30)->toDateString();
        
        $production = Production::create($validated);

        // Auto-update stock
        $product = Product::find($validated['product_id']);
        $product->increment('stok', $validated['quantity']);

        return redirect()->back()->with('success', 'Produksi berhasil dicatat dan stok bertambah.');
    }

    public function report(Request $request)
    {
        $query = Production::with('product')->latest('production_date');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('production_date', [$request->start_date, $request->end_date]);
        }

        $productions = $query->get();
        $products = Product::orderBy('nama')->get()->groupBy('kategori');

        return view('productions.report', compact('productions', 'products'));
    }

    public function print(Production $production)
    {
        $production->load('product');
        return view('productions.print', compact('production'));
    }
}
