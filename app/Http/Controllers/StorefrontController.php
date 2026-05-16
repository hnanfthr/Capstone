<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Banner;
use App\Models\Setting;

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

        $banners = Banner::where('is_active', true)->orderBy('id', 'desc')->get();
        
        // Fetch all settings and default values
        $dbSettings = Setting::all()->pluck('value', 'key');
        $settings = [
            'promo_is_active' => $dbSettings['promo_is_active'] ?? '1',
            'promo_badge' => $dbSettings['promo_badge'] ?? 'SPESIAL MINGGU INI',
            'promo_title' => $dbSettings['promo_title'] ?? 'Paket Bundling Keluarga 👨‍👩‍👧‍👦',
            'promo_desc' => $dbSettings['promo_desc'] ?? 'Beli 3 toples jenis apa saja, dapatkan potongan harga spesial dan Gratis Kartu Ucapan Premium untuk orang tersayang.',
            'promo_valid_until' => $dbSettings['promo_valid_until'] ?? 'Berlaku s.d akhir bulan',
            'promo_discount_text' => $dbSettings['promo_discount_text'] ?? '20%',
        ];

        return view('storefront.index', compact('bestSellers', 'topRated', 'banners', 'settings'));
    }

    public function catalog()
    {
        $productsByCategory = Product::where('stok', '>', 0)->get()->groupBy('kategori');
        return view('storefront.catalog', compact('productsByCategory'));
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
