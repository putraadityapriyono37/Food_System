<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function orderOptionsAreValid(): bool
    {
        $orderType = session('order_type');
        $tableId = session('table_id');

        // Opsi valid jika:
        // 1. Tipe pesanan adalah 'bawa pulang'
        // ATAU
        // 2. Tipe pesanan adalah 'makan di tempat' DAN nomor meja sudah dipilih.
        return ($orderType === 'take_away') || ($orderType === 'dine_in' && !empty($tableId));
    }

    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Menambahkan produk biasa ke keranjang.
     */
    public function add(Request $request, Product $product)
    {
        if (!$this->orderOptionsAreValid()) {
            return response()->json(['success' => false, 'message' => 'Pilih Tipe Pesanan & Meja Dahulu!'], 400);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'sometimes|nullable|exists:product_variants,id'
        ]);

        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');
        $variantId = $request->input('variant_id');

        $cartItemId = $product->id;
        $itemName = $product->name;
        $itemPrice = $product->price;
        $itemImage = $product->image;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            $cartItemId .= '-' . $variant->id;
            $itemName .= ' (' . $variant->size . ')';
            $itemPrice = $variant->price;
        }

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $quantity;
        } else {
            $cart[$cartItemId] = [
                "product_id" => $product->id,
                "name"       => $itemName,
                "quantity"   => $quantity,
                "price"      => $itemPrice,
                "image_path" => $itemImage
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan!',
            'cart_count' => count(session('cart'))
        ]);
    }

    /**
     * Mengupdate kuantitas item di keranjang.
     */
    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $cart = session()->get('cart', []);

        if (isset($cart[$cartItemId])) {
            // FIX: Tambahkan penjaga ini
            // Jika item adalah bundel, jangan lakukan apa-apa (tidak bisa di-update).
            if (isset($cart[$cartItemId]['is_bundle'])) {
                return back()->with('info', 'Kuantitas paket promo tidak dapat diubah.');
            }

            $quantity = (int) $request->quantity;
            if ($quantity > 0) {
                $cart[$cartItemId]['quantity'] = $quantity;
            } else {
                unset($cart[$cartItemId]); // Hapus jika kuantitas 0
            }
            session()->put('cart', $cart);
            return back()->with('success', 'Keranjang berhasil diperbarui.');
        }
        return back()->with('error', 'Item tidak ditemukan.');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove($cartItemId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            session()->put('cart', $cart);
            return back()->with('success', 'Item berhasil dihapus dari keranjang.');
        }
        return back()->with('error', 'Item tidak ditemukan.');
    }

    public function setCustomerName(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255'
        ]);

        session(['customer_name' => $validated['customer_name'] ?? null]);

        return response()->json(['success' => true]);
    }

    /**
     * Menambahkan paket promo ke keranjang.
     */
    public function addBundle(Promotion $promotion)
    {
        // FIX: Tambahkan blok validasi di bagian paling atas
        if (!$this->orderOptionsAreValid()) {
            return back()->with('error', 'Silakan pilih Tipe Pesanan dan Meja Anda terlebih dahulu!');
        }
        if (!$promotion->is_active) {
            return response()->json(['success' => false, 'message' => 'Promo ini sudah tidak berlaku.'], 404);
        }

        $cart = session()->get('cart', []);
        $promoData = json_decode($promotion->promo_data);
        $bundleCartId = 'bundle_' . $promotion->id;

        if (isset($cart[$bundleCartId])) {
            return response()->json([
                'success' => false,
                'message' => 'Paket promo ini sudah ada di keranjang Anda.'
            ]);
        }

        $cart[$bundleCartId] = [
            "name"       => $promotion->title,
            "quantity"   => 1,
            "price"      => $promoData->package_price,
            "image_path" => $promotion->image_path,
            "is_bundle"  => true,
            "items"      => $promotion->products->pluck('name')->toArray(),
        ];

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Paket promo berhasil ditambahkan!',
            'cart_count' => count(session('cart'))
        ]);
    }
}
