<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    // File: database/seeders/ProductSeeder.php

    public function run(): void
    {
        // Buat produk burger
        $burger = Product::create([
            'name' => 'Burger Lorem',
            'slug' => 'burger-lorem',
            'description' => 'A classic hamburger featuring a juicy beef patty, fresh lettuce, and our signature secret sauce.',
            'time_estimation' => 20, // <-- TAMBAHKAN INI
            'price' => 25000, // <-- TAMBAHKAN KEMBALI BARIS INI (harga varian terkecil)
            'category' => 'makanan',
            'is_available' => true,
            'is_best_seller' => true,
            'image' => null,
        ]);

        // Buat varian untuk burger tersebut
        $burger->variants()->create(['size' => '10"', 'price' => 25000]);
        $burger->variants()->create(['size' => '14"', 'price' => 35000]);
        $burger->variants()->create(['size' => '16"', 'price' => 42000]);

        // Buat produk minuman (tanpa varian)
        $icedTea = Product::create([
            'name' => 'Classic Iced Tea',
            'slug' => 'classic-iced-tea',
            'description' => 'A refreshing sweet iced tea, brewed to perfection.',
            'time_estimation' => 5, // <-- TAMBAHKAN INI
            'price' => 8000, // <-- Untuk produk tanpa varian, harga ini adalah harga tetapnya
            'category' => 'minuman',
            'is_available' => true,
            'is_best_seller' => true,
            'image' => null,
        ]);
    }
}
