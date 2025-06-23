<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Langkah 1: Jadikan kolom product_id bisa null (nullable).
            // Ini penting karena item "Paket Promo" tidak memiliki satu product_id.
            // Kita perlu menggunakan ->change() untuk memodifikasi kolom yang sudah ada.
            $table->foreignId('product_id')->nullable()->change();

            // Langkah 2: Tambahkan kolom baru untuk promotion_id.
            // Kolom ini akan menyimpan ID dari paket promo.
            // Kolom ini juga bisa null, untuk item yang bukan promo.
            // onDelete('set null') berarti jika promo dihapus, nilai di sini akan menjadi NULL.
            $table->foreignId('promotion_id')->nullable()->after('product_id')->constrained()->onDelete('set null');

            // Langkah 3: Tambahkan kolom baru untuk item_details.
            // Kolom JSON ini akan menyimpan detail item, seperti nama produk
            // atau daftar produk di dalam sebuah paket.
            $table->json('item_details')->nullable()->after('price_per_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Perintah untuk membatalkan perubahan jika diperlukan
            $table->dropForeign(['promotion_id']);
            $table->dropColumn(['promotion_id', 'item_details']);
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
