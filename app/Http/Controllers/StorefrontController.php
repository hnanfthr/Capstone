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
        // Products query removed since we are fetching bestSellers and topRated directly
        
        $bestSellers = Product::has('orderItems')
            ->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->take(4)
            ->get();

        $topRated = Product::has('reviews')
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
            'whatsapp_number' => $dbSettings['whatsapp_number'] ?? '6281339263950',
        ];

        return view('storefront.index', compact('bestSellers', 'topRated', 'banners', 'settings'));
    }

    public function catalog()
    {
        $dbSettings = Setting::all()->pluck('value', 'key');
        $waNumber = $dbSettings['whatsapp_number'] ?? '6281339263950';
        $productsByCategory = Product::all()->groupBy('kategori');
        return view('storefront.catalog', compact('productsByCategory', 'waNumber'));
    }

    public function show(Product $product)
    {
        $product->load('reviews');
        $dbSettings = Setting::all()->pluck('value', 'key');
        $waNumber = $dbSettings['whatsapp_number'] ?? '6281339263950';
        return view('storefront.show', compact('product', 'waNumber'));
    }

}
