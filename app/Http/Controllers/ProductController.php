<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Table;
use App\Models\Promotion;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $searchQuery = $request->query('q');

        // Logika baru untuk menyimpan Opsi Pesanan dari URL ke Session
        if ($request->has('order_type')) {
            $validated = $request->validate([
                'order_type' => 'in:dine_in,take_away',
                'table_id' => 'nullable|exists:tables,id',
            ]);

            session(['order_type' => $validated['order_type']]);

            if ($request->order_type === 'dine_in') {
                session(['table_id' => $validated['table_id'] ?? null]);
            } else {
                $request->session()->forget('table_id');
            }
        }


        // Query dasar sekarang selalu menyertakan data varian.
        $baseQuery = Product::with('variants')->where('is_available', true);

        // Selalu ambil produk populer untuk rekomendasi di overlay pencarian
        $popularProducts = (clone $baseQuery)->where('is_best_seller', true)->take(4)->get();

        if ($searchQuery) {
            $products = (clone $baseQuery)->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('description', 'LIKE', "%{$searchQuery}%");
            })->latest()->get();
            $bestSellers = collect();
            $sectionTitle = "Hasil Pencarian untuk '$searchQuery'";
        } else {
            $bestSellersQuery = (clone $baseQuery)->where('is_best_seller', true);
            $bestSellersQuery->when($category, fn($q, $c) => $q->where('category', $c));
            $bestSellers = $bestSellersQuery->latest()->take(4)->get();

            $productsQuery = clone $baseQuery;
            $productsQuery->when($category, fn($q, $c) => $q->where('category', $c));
            $products = $productsQuery->latest()->get();

            $sectionTitle = 'Semua Produk';
            if ($category == 'makanan') {
                $sectionTitle = 'Aneka Makanan';
            } elseif ($category == 'minuman') {
                $sectionTitle = 'Aneka Minuman';
            }
        }

        // 1. Ambil data meja yang tersedia
        $availableTables = Table::where('status', 'available')->orderBy('name')->get();

        // 2. Ambil data promo yang aktif
        $promotions = Promotion::where('is_active', true)
            ->latest()
            ->get();

        // Logika untuk mengecek status pilihan pesanan
        $orderType = session('order_type');
        $tableId = session('table_id');
        $isOrderOptionSet = ($orderType === 'take_away') || ($orderType === 'dine_in' && !empty($tableId));

        // 3. Kirim semua data ke view dalam SATU KALI return
        return view('home', [
            'products'        => $products,
            'bestSellers'     => $bestSellers,
            'popularProducts' => $popularProducts,
            'activeCategory'  => $category ?? 'all',
            'sectionTitle'    => $sectionTitle,
            'availableTables' => $availableTables,
            'promotions'      => $promotions,
            'isOrderOptionSet' => $isOrderOptionSet,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('variants');
        $orderType = session('order_type');
        $tableId = session('table_id');
        $isOrderOptionSet = ($orderType === 'take_away') || ($orderType === 'dine_in' && !empty($tableId));

        return view('product-detail', [
            'product' => $product,
            'isOrderOptionSet' => $isOrderOptionSet, // Kirim status ke view
        ]);
    }
}
