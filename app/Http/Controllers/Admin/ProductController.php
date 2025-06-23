<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     */
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->withCount('variants')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Menyimpan produk baru beserta variannya ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'category' => 'required|in:makanan,minuman',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Limit 10MB
            'time_estimation' => 'nullable|integer|min:0',
            'is_best_seller' => 'required|boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price' => 'required_if:category,minuman|nullable|numeric|min:0',
            'variants' => 'required_if:category,makanan|nullable|array',
            'variants.*.size' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $productData = $validated;
            $productData['slug'] = Str::slug($request->name);

            // Tentukan harga utama produk
            if ($request->category === 'makanan' && !empty($validated['variants'])) {
                // Jika makanan, gunakan harga varian pertama sebagai harga utama
                $productData['price'] = $validated['variants'][0]['price'];
            }
            // Jika minuman, harga sudah ada dari input 'price'

            if ($request->hasFile('image')) {
                // Gunakan nama kolom 'image' sesuai database
                $productData['image'] = $request->file('image')->store('products', 'public');
            }

            // Buat produk utama
            $product = Product::create($productData);

            // Jika kategori adalah makanan, simpan varian-variannya
            if ($request->category === 'makanan' && isset($validated['variants'])) {
                foreach ($validated['variants'] as $variantData) {
                    $product->variants()->create($variantData);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        $product->load('variants');
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Mengupdate produk beserta variannya di database.
     */
    // app/Http/Controllers/Admin/ProductController.php

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'required|string',
            'category' => 'required|in:makanan,minuman',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'time_estimation' => 'nullable|integer|min:0',
            'is_best_seller' => 'required|boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price' => 'required_if:category,minuman|nullable|numeric|min:0',
            'variants' => 'required_if:category,makanan|nullable|array',
            'variants.*.size' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $product, $validated) {
            $productData = $validated;
            $productData['slug'] = Str::slug($request->name);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $productData['image'] = $request->file('image')->store('products', 'public');
            }

            // Tentukan harga utama produk
            if ($request->category === 'makanan' && !empty($validated['variants'])) {
                $productData['price'] = $validated['variants'][0]['price'];
            }

            // Update data produk utama
            $product->update($productData);

            // --- INI LOGIKA SINKRONISASI BARU YANG LEBIH AMAN ---
            // 1. Hapus semua varian lama
            $product->variants()->delete();

            // 2. Jika kategori adalah makanan, buat ulang varian dari data form
            if ($request->category === 'makanan' && isset($validated['variants'])) {
                foreach ($validated['variants'] as $variantData) {
                    $product->variants()->create($variantData);
                }
            }
            // --- AKHIR LOGIKA SINKRONISASI ---
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk beserta gambar dan variannya.
     */
    public function destroy(Product $product)
    {
        // Gunakan DB::transaction untuk memastikan semua proses berjalan atau tidak sama sekali
        DB::transaction(function () use ($product) {

            // 1. Hapus gambar dari storage jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // 2. Hapus semua varian yang terhubung dengan produk ini
            // Ini akan menjalankan query DELETE FROM product_variants WHERE product_id = ...
            $product->variants()->delete();

            // 3. Setelah semua data terkait aman dihapus, hapus produk utama
            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
