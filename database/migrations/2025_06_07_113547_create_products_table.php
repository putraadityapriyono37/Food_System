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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap produk
            $table->string('name'); // Nama produk
            $table->text('description')->nullable(); // Deskripsi singkat, boleh kosong
            $table->decimal('price', 10, 2); // Harga produk
            $table->string('image')->nullable(); // Path atau URL ke gambar produk
            $table->enum('category', ['makanan', 'minuman']); // Kategori produk
            $table->boolean('is_available')->default(true); // Status ketersediaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
