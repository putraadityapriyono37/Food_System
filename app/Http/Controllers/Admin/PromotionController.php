<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // <-- TAMBAHKAN INI
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    /**
     * Menampilkan daftar semua promo.
     */
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Menampilkan form untuk membuat promo baru.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get(); // Ambil semua produk untuk ditampilkan di dropdown
        return view('admin.promotions.create', compact('products'));
    }

    /**
     * Menyimpan promo baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:10240',
            'product_ids'   => 'required|array', // Pastikan ini adalah array
            'product_ids.*' => 'exists:products,id', // Pastikan setiap ID di dalam array ada di tabel products
            'package_price' => 'required|numeric|min:0', // Ganti 'promo_price' menjadi 'package_price'
            'is_active' => 'required|boolean',
        ]);

        $promoData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $request->file('image')->store('promotions', 'public'),
            'is_active' => $validated['is_active'],
            'type' => 'special_price',
            'promo_data' => json_encode([
                'product_ids'   => $validated['product_ids'], // Simpan array ID
                'package_price' => $validated['package_price'] // Simpan harga paket
            ])
        ];

        Promotion::create($promoData);
        return redirect()->route('admin.promotions.index')->with('success', 'Promo baru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit promo.
     */
    public function edit(Promotion $promotion)
    {
        $products = Product::orderBy('name')->get();
        // Decode promo_data untuk dikirim ke view
        $promotion->promo_data = json_decode($promotion->promo_data);
        return view('admin.promotions.edit', compact('promotion', 'products'));
    }

    /**
     * Mengupdate promo yang sudah ada.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:10240', // Gambar tidak wajib diupdate
            'product_ids'   => 'required|array', // Pastikan ini adalah array
            'product_ids.*' => 'exists:products,id', // Pastikan setiap ID di dalam array ada di tabel products
            'package_price' => 'required|numeric|min:0', // Ganti 'promo_price' menjadi 'package_price'
            'is_active' => 'required|boolean',
        ]);

        $promoData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'],
            'promo_data' => json_encode([
                'product_ids'   => $validated['product_ids'], // Simpan array ID
                'package_price' => $validated['package_price'] // Simpan harga paket
            ])
        ];

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            Storage::disk('public')->delete($promotion->image_path);
            // Simpan gambar baru
            $promoData['image_path'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion->update($promoData);
        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Menghapus promo dari database.
     */
    public function destroy(Promotion $promotion)
    {
        // Hapus gambar dari storage
        Storage::disk('public')->delete($promotion->image_path);
        // Hapus data dari database
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil dihapus.');
    }
}
