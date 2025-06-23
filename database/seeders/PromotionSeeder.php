<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        // Promo 1: Paket Hemat (Burger + Es Teh)
        Promotion::create([
            'title' => 'Promo Paket Hemat',
            'description' => 'Burger Klasik dan Es Teh Lemon dengan harga spesial!',
            'image_path' => 'images/promotions/promo-paket-hemat.png',
            'type' => 'bundle',
            'promo_data' => json_encode([
                'items' => [
                    ['product_id' => 3, 'quantity' => 1], // Ganti 1 dengan ID Burger Anda
                    ['product_id' => 4, 'quantity' => 1]  // Ganti 2 dengan ID Es Teh Anda
                ],
                'bundle_price' => 35000 // Harga paket
            ]),
            'is_active' => true,
        ]);

        // Promo 2: Beli 1 Gratis 1 Burger
        Promotion::create([
            'title' => 'Beli 1 Gratis 1 Burger',
            'description' => 'Beli satu Burger Mewah, dapatkan satu lagi gratis!',
            'image_path' => 'images/promotions/promo-bogo.png',
            'type' => 'bundle',
            'promo_data' => json_encode([
                'items' => [
                    ['product_id' => 3, 'quantity' => 2] // Ganti 1 dengan ID Burger Anda
                ],
                'bundle_price' => 30000 // Harga untuk 2 burger (harga 1 burger)
            ]),
            'is_active' => true,
        ]);
    }
}
