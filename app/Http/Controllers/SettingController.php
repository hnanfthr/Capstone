<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Banner;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('id', 'desc')->get();
        
        // Ambil semua settings, ubah jadi collection dengan key sebagai index
        $settings = Setting::all()->pluck('value', 'key');
        
        // Nilai default jika belum ada di database
        $defaultSettings = [
            'promo_is_active' => '1',
            'promo_badge' => 'SPESIAL MINGGU INI',
            'promo_title' => 'Paket Bundling Keluarga 👨‍👩‍👧‍👦',
            'promo_desc' => 'Beli 3 toples jenis apa saja, dapatkan potongan harga spesial dan Gratis Kartu Ucapan Premium untuk orang tersayang.',
            'promo_valid_until' => 'Berlaku s.d akhir bulan',
            'promo_discount_text' => '20%',
        ];

        // Gabungkan default dengan setting yang ada di database
        foreach ($defaultSettings as $key => $value) {
            if (!isset($settings[$key])) {
                $settings[$key] = $value;
            }
        }

        return view('settings.index', compact('banners', 'settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan teks promo berhasil diperbarui!');
    }
}
