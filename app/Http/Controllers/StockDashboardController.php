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

        return view('stocks.dashboard', compact('products', 'todayProductions'));
    }
}
