<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class StorefrontController extends Controller
{
    public function index()
    {
        $products = Product::where('stok', '>', 0)->get();
        
        $bestSellers = Product::where('stok', '>', 0)
            ->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->take(4)
            ->get();

        $topRated = Product::where('stok', '>', 0)
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(4)
            ->get();

        return view('storefront.index', compact('products', 'bestSellers', 'topRated'));
    }

    public function show(Product $product)
    {
        $product->load('reviews');
        return view('storefront.show', compact('product'));
    }

    public function track()
    {
        return view('storefront.track');
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $search = $request->search;
        
        $orders = Order::with('items.product')
            ->where('order_no', $search)
            ->orWhere('whatsapp_number', $search)
            ->latest()
            ->get();

        return view('storefront.track', compact('orders', 'search'));
    }
}
