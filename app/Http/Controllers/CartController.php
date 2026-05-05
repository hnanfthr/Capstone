<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('storefront.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $currentQuantity = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        $newQuantity = $currentQuantity + $request->quantity;

        if ($newQuantity > $product->stok) {
            return redirect()->back()->with('error', 'Maaf, pesanan melebihi sisa stok (Sisa: ' . $product->stok . ' toples).');
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->nama,
                "quantity" => $request->quantity,
                "price" => $product->harga,
                "image" => $product->foto
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
        }
    }
}
